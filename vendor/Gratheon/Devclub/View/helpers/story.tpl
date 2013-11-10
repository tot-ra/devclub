{literal}
<script type="text/template" id="story_item_template">

	<div class="leftTop">
		<img src="/vendor/Gratheon/Devclub/assets/img/drag_icon.gif" class="draghandle" />
		<strong class="draghandle"><%=obj.title%></strong>


		<div style="display:none;" class="extra">
			<em style="padding:5px 0; display:block;"><%=obj.description%></em>
			<div style="clear:both;"></div>
		</div>
	</div>

	<div class="rightTop">
		<% if(obj.owner){ %>
			<a class="close logged_in" href="#">&times;</a>
			<i class="icon-pencil logged_in"></i>
		<% } %>
	</div>


	<div class="leftBottom">
		<% if(obj.status=='icebox'){ %>
			<a class="vote btn btn-mini logged_in" href="#"><i class="icon-thumbs-up"></i></a>
			<a class="unvote btn btn-mini logged_in" href="#"><i class="icon-minus"></i></a>
		<% } %>

		<% if(obj.gravatar){%>
			<img src="http://gravatar.com/avatar/<%=obj.gravatar%>?s=20" style="float:left;margin-right:5px;"/>
		<%}%>
		<span class="authors">
			<%=obj.authors%>
		</span>

		<% if(obj.status=='completed'){ %>
			<a class="yearvote btn btn-mini logged_in" href="#"><i class="icon-plus-sign"></i></a>
			<a class="yearunvote btn btn-mini logged_in" href="#"><i class="icon-minus"></i></a>
			<span class="label label-important" rel="tooltip" title="число голосовавших скрыто"><i class="icon-user"></i> <%=votes%></span>
		<% } %>
	</div>


	<div class="rightBottom">
		<span class="label">
			<i class="icon-time"></i> <%=obj.duration%>
			<% if(obj.rate) { %>
				<i rel="tooltip" title="число голосовавших" class="icon-user"></i> <%=votes%>
				<i rel="tooltip" title="среднее по позициям: <%=distribution%>" class="icon-fire"></i> <%=rate%>
			<% } %>
		</span>
	</div>
	<div style="clear:both;"></div>
</script>
{/literal}