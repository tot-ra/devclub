<?php
/**
 * @author Artjom Kurapov
 * @since 20.06.12 15:44
 */

class devclub extends Controller {

    private $usersCanReorder = array('artkurapov@gmail.com');

    private function getEmail() {
        return $_SESSION[__CLASS__]['auth_email'];
    }

    function main() {
        $this->add_css('bootstrap.css');
        $this->add_css('bootstrap-responsive.css');
        $this->add_css('main.css');
        $this->add_css('/cms/external_libraries/jquery_ui/ui-lightness/jquery-ui-1.8.10.custom.css', false);

        $this->add_js('https://browserid.org/include.js');

        $this->add_js('/cms/external_libraries/jquery/1.7.1.js');
        $this->add_js('bootstrap.min.js');
        $this->add_js('/cms/external_libraries/backbone/underscore-min.js');
        $this->add_js('/cms/external_libraries/backbone/backbone.js');
        //$this->add_js('backbone-0.9.2.js');
        $this->add_js('/cms/external_libraries/jquery_ui/jquery-ui-1.8.10.full.min.js');

        $this->add_js('touch-punch.js');
        $this->add_js('main.js');

        $this->add_js_var('sys_url', sys_url . 'devclub/');

        $this->smarty('email', $this->getEmail());

        $votes = new Model('devclub_vote');
        $this->smarty('voted', $votes->int("user='".$this->getEmail()."'","COUNT(*)"));

        return $this->view('main.tpl');
    }

    function story() {
        global $input;

        if(!$this->getEmail()){
            return false;
        }

        $stories = new Model('devclub_story');
        $votes = new Model('devclub_vote');

        $params = (array)json_decode(file_get_contents('php://input'));

        $position = $params['position'];
        $status = $params['status'];
        unset($params['position']);

        $ID = (int)$input->PID[3];
        if ($ID) {
            $story = $stories->obj($ID);

            if (!$this->canReorder()) {
                unset($params['status']);
            }
            unset($params['creator_email']);

            if($params['title']){
                $params['title'] = htmlentities ($params['title'], ENT_COMPAT, 'UTF-8');
                $params['authors'] = htmlentities ($params['authors'], ENT_COMPAT, 'UTF-8');
                $params['description'] = htmlentities ($params['description'], ENT_COMPAT, 'UTF-8');
            }

            if ($params) {
                $stories->update($params, "ID='$ID'");
            }

            echo json_encode($story);
        }
        else {
            $ID = $stories->insert(array(
                'title' => htmlentities ($params['title'], ENT_COMPAT, 'UTF-8'),
                'authors' => htmlentities ($params['authors'], ENT_COMPAT, 'UTF-8'),
                'description' => htmlentities ($params['description'], ENT_COMPAT, 'UTF-8'),
                'duration' => (int)$params['duration'],
                'creator_email' => $this->getEmail()
            ));

            $story = $stories->obj($ID);
        }

        if($status=='icebox'){
            $votes->delete("storyID='$ID' AND `user`='" . $this->getEmail() . "'");


            //recreate proper order for single user
            $userVotes = $votes->arr(
                "`user`='" . $this->getEmail() . "' ORDER BY POSITION ASC", "*",
                "devclub_vote t1 INNER JOIN devclub_story t2 ON t2.ID=t1.storyID AND t2.status='icebox'"
            );

            $i=0;
            foreach($userVotes as $vote){
                if($i==$position){
                    $votes->insert(array(
                        'storyID' => $ID,
                        'user' => $this->getEmail(),
                        'position' => $position
                    ));
                    $i++;
                }

                $votes->update(array('position'=>$i), "storyID='{$vote->storyID}' AND user='" . $this->getEmail() . "'");
                $i++;
            }
        }

        echo json_encode($story);
    }

    function list_current_stories() {
        $stories = new Model('devclub_story');
        echo json_encode($stories->arr("status='current'",
            "*, '' votes, '' rate, '0' voted, '' position"));
    }

    function list_backlog_stories() {
        $stories = new Model('devclub_story');
        echo json_encode($stories->arr("status='backlog'",
            "*, '' votes, '' rate, '0' voted, '' position"));
    }

    function list_icebox_stories() {
        $stories = new Model('devclub_story');
        $vote = new Model('devclub_vote');

        $list = $stories->arr(
            "status='icebox'
            GROUP BY t1.ID
            ORDER BY isnull ASC, isnull2 ASC, t3.position ASC, avgPosition ASC",

            "t1.*, AVG(t2.position) avgPosition, t3.position IS NULL AS isnull, AVG(t2.position) IS NULL AS isnull2, t3.position, t1.ID as id",

            "devclub_story t1
            LEFT JOIN devclub_vote t2 ON t1.ID=t2.storyID
            LEFT JOIN devclub_vote t3 ON t1.ID=t3.storyID AND t3.user='".$this->getEmail()."'
            ");

        foreach($list as &$topic){

            $topic->voted = $vote->int("storyID='".$topic->ID."' AND user='".$this->getEmail()."'", "COUNT(*)");
            $topic->votes = $vote->int("storyID='".$topic->ID."'", "COUNT(user)");
            $topic->rate = round(100*(float)$vote->int("storyID='".$topic->ID."'", "1 + AVG(position)"))/100;
        }

        echo json_encode($list);
    }

    function delete_story() {
        global $input;
        $ID = (int)$input->PID[3];
        $stories = new Model('devclub_story');
        $story = $stories->obj($ID);
        if ($story && ($this->canReorder() || $story->creator_email == $this->getEmail())) {
            $stories->delete($ID);
        }
    }

    function logout() {
        unset($_SESSION[__CLASS__]['auth_email']);
    }

    function login() {
        $url = 'https://browserid.org/verify';
        $data = http_build_query(array(
            'assertion' => $_POST['assertion'],
            'audience' => urlencode('gratheon.com')
        ));

        $params = array(
            'http' => array(
                'method' => 'POST',
                'content' => $data,
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                        . "Content-Length: " . strlen($data) . "\r\n"
            )
        );

        $ctx = stream_context_create($params);
        $fp = fopen($url, 'rb', false, $ctx);

        if ($fp) {
            $result = stream_get_contents($fp);
        }
        else {
            $result = FALSE;
        }

        $json = json_decode($result);

        if ($json->status == 'okay') {
            $_SESSION[__CLASS__]['auth_email'] = $json->email;
            // the user logged in successfully.
        }
        else {
            // log in failed.
        }

        echo $result;
    }

    function canReorder() {
        return in_array($this->getEmail(), $this->usersCanReorder);
    }

    function author_list(){
        if(strlen($_GET['term'])<2){
            return false;
        }

        $name = mysql_real_escape_string($_GET['term']);
        $devclub_story = new Model('devclub_story');
        echo json_encode($devclub_story->arrint("authors LIKE '%$name%'","DISTINCT(authors)"));
    }
}