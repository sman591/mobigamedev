$.ajaxPrefilter( function( options, originalOptions, jqXHR ) {
  options.url = '/server' + options.url;
});

$.fn.serializeObject = function() {
  var o = {};
  var a = this.serializeArray();
  $.each(a, function() {
      if (o[this.name] !== undefined) {
          if (!o[this.name].push) {
              o[this.name] = [o[this.name]];
          }
          o[this.name].push(this.value || '');
      } else {
          o[this.name] = this.value || '';
      }
  });
  return o;
};

function debug(message) {
	
	if (window.app.debug === true)
		console.log(message);
	
}


Backbone.View.prototype.close = function () {
  this.$el.empty();
  this.unbind();
};

/* PAGE */

var Page = Backbone.Model.extend({
	
	urlRoot: '/page',
	
	defaults: {
		title:		'',
		content:	''
	}
	
});

var PageView = Backbone.View.extend({
	
	className: "pageView",
	id:			"page-view",
	
	initialize: function(){
	
		this.listenTo(this.model, "change", this.render);
	
	},
	
	render: function(){
		
		this.updateNav();
		
		var html = '<div class="page">' + this.model.get('content') + '</div>';
		this.$el.html(html);
		
		return this;
		
		/* Set content by calling for $().html(page.el); Make sure the page is rendered first! */
		
	},
	
	updateNav: function(){
		
		slug = this.model.get('slug');
		
		$(".nav li").removeClass("active");
		$('.nav li a[href="#/'+slug+'"]').parent().addClass("active");
		$('.nav li a[href="#/page/'+slug+'"]').parent().addClass("active");
	
	},
	
});


/* USER */

var User = Backbone.Model.extend({
	
	urlRoot: '/user',
	
	defaults: {
		name_first:		'',
		name_last:		'',
		email:			''
	}
	
});

var UserView = Backbone.View.extend({
	
	className: "userView",
	id:			"user-view",
	
	events: {
		'submit .UserViewForm': 'saveUser'
	},
	
	initialize: function(){
	
		this.listenTo(this.model, "change", this.render);
	
	},
	
	template: _.template($('#userView_template').html()),
	
	render: function(){
		
		var attributes = this.model.toJSON();
		this.$el.html(this.template(attributes));
		
		return this;
		
	},
	
	saveUser: function(){
		
		var $save_btn = this.$el.children('.UserViewForm button[type=submit]');
		
		var user_data = $(this.$el.children('form')).serializeObject();
		
		var that = this;
		
		this.model.save(user_data, {
			success: function () {
				$('.UserViewForm button').after('<i class="icon-ok successMark" style="display: none; margin-left: 14px;"></i>');
				$('.UserViewForm .successMark').fadeIn(200).delay(2000).fadeOut(200, function(){
					$('.UserViewForm .successMark').remove();
				});
			}
        });
		
        return false;
	
	}
	
});


/* PROJECT */

var Project = Backbone.Model.extend({
	
	urlRoot: '/project',
	
});

var ProjectView = Backbone.View.extend({
	
	className: 'projectView',
	id: 'project-view',
	edit: false,
	currentTab: '',
	
	events: {
		"click [data-href=contribute]"	: "tabTriggered",
		"click [data-href=overview]"	: "tabTriggered",
		"click [data-href=data]"		: "tabTriggered",
		"click [data-href=info]"		: "tabTriggered",
		"click [data-href=edit]"		: "toggleEdit"
	},
	
	initialize: function(){
	
		this.model.on("change", this.render, this);
		
		/*
if (this.model.get('title') !== undefined)
			this.render();
*/
	
	},
	
	template: _.template($('#projectView_template').html()),
	
	render: function(){
		
		debug('Project' + (this.edit == true ? "Edit" : "") + 'View Rendered'); // Debugging
		
		var attributes = this.model.toJSON();
		this.$el.html(this.template(attributes));
		
		this.changeTab(this.currentTab);
		
		$(".project-carousel .carousel-caption h1").fitText(0.87, {minFontSize: '60px'});
		$(".project-carousel .carousel-caption p").fitText(5.3, {minFontSize: '16px'});
		
		return this;
		
	},
	
	tabTriggered: function(e){

		var $e = $(e.currentTarget);
		
		window.app.router.navigate("//project/" + this.model.get("id") + "/" + (this.edit == true ? "edit/" : "") + $e.attr('data-href'), true);
	
	},
	
	changeTab: function(tab){
		
		this.currentTab = tab;
		
		var $e = this.$el.find('[data-href=' + this.currentTab + ']');
		
		/* Update navigation */
		this.$el.find('.tile-nav .tile.active').removeClass('active');
		$e.addClass('active');
		
		/* Update content */
		this.$el.find('.tab-content').hide();
		this.$el.find('#tab-' + this.currentTab).show();
	
	},
	
	toggleEdit: function() {
		
		window.app.router.navigate("//project/" + this.model.get('id') + "/" + (this.edit == false ? "edit/" : "") + this.currentTab, true);
		
	}
	
});

var ProjectEditView = ProjectView.extend({
	
	className: 'projectEditView',
	id: 'project-edit-view',
	edit: true,
	
	events: {
		"click [data-href=contribute]"	: "tabTriggered",
		"click [data-href=overview]"	: "tabTriggered",
		"click [data-href=data]"		: "tabTriggered",
		"click [data-href=info]"		: "tabTriggered",
		"click [data-href=done]"		: "doneProject",
		"click [data-href=save]"		: "saveProject",
		"submit form"					: "saveProject",
		"click [data-href=edit]"		: "toggleEdit"
	},
	
	template: _.template($('#projectEditView_template').html()),
	
	doneProject: function(){
		
		this.saveProject();
		
		this.toggleEdit();
		
	},
	
	saveProject: function(){
		
		$('textarea.mceEditor').each(function(){
			
			id = $(this).attr('id');
			tinyMCE.get(id).save();
			
		});
		
		var data = $(this.$el.find('form')).serializeObject();
		
		data.id = this.model.get('id');
		
		var that = this;
		
		this.model.save(data, {
			success: function () {
				$('[data-href=save]').before('<i class="icon-white icon-ok successMark" style="display: none; margin-right: 14px;"></i>');
				that.$el.find('.successMark').fadeIn(200).delay(3000).fadeOut(200, function(){
					that.$el.find('.successMark').remove();
				});
			}
        });
		
        return false;
	
	}
	
});


/* ROUTER */

var AppRouter = Backbone.Router.extend({
	
	previousRoute: '',
	
	routes: {
		
		""							: "index",
		"page/:id"					: "showPage",
		"account"					: "showAccount",
		"projects"					: "projectIndex",
		"project/:id/edit(/:tab)"	: "editProject",
		"project/:id(/:tab)"		: "showProject"
		
	},
	
	getSegment: function(index) {
		
		var segments = Backbone.history.fragment.split('/');
		return segments[index - 1] || false;
		
	},
	
	start: function(){

		Backbone.history.start();
	
	},
	
	before: function(route) {
	
		if (this.previousRoute == '') {
			this.previousRoute = this.routes[route];
			debug('previousRoute set to ' + this.previousRoute + " (before)");
		}
		
	},
	
	after: function( route ) {
	
		if (this.previousRoute !== this.routes[route]) {
			this.previousRoute = this.routes[route];
			debug('previousRoute set to ' + this.previousRoute);
		}
		else
			debug('previousRoute is the same');
		
	},
	
	initialize: function() {
	
		this._bindRoutes();	
		
	},
	
	index: function(){
	
		if (window.location.pathname == '/')
			this.showPage();
	
	},
	
	showPage: function(id){

		if (!this.page)
			this.page = new Page();
		if (!this.paveView)
			this.pageView = new PageView({model: this.page});

		this.page.on("request", loading_notice('show'));
		this.page.on("sync", loading_notice('hide'));

		this.page.set('id', id);
		this.page.fetch();
		
		var that = this;
		
		this.page.on("sync", function() {$('#guts').html(that.pageView.el)});
	
	},
	
	showAccount: function(){
		
		if (!this.user)
			this.user = new User();
		
		if (!this.userView)
			this.userView = new UserView({model: this.user});
		
		$('#guts').html(this.userView.el);
		
		this.user.fetch();
	
	},

	projectIndex: function(){
		
		this.showPage('projects');
	
	},
	
	init_project: function(id) {
		
		this.project = new Project({id: id});

		this.project.on("request", loading_notice('show'));
		this.project.on("sync", loading_notice('hide'));
		
	},
	
	showProject: function(id, tab){
		
		if (tab == undefined) {
			this.navigate("//project/" + id + "/overview", true);
			return false;
		}
		
		if (!this.project || this.project.get('id') !== id)
			this.init_project(id);

		if (this.projectView == undefined || this.previousRoute !== 'showProject' || this.projectView.model.get("id") !== id) {
			this.projectView = new ProjectView({model: this.project});
			$('#guts').html(this.projectView.el);
			this.projectView.currentTab = tab;
			this.project.fetch();
		}
		else {
			this.projectView.changeTab(tab);
		}
		
		if (this.previousRoute !== 'showProject')
			this.projectView.render();
	
	},
	
	editProject: function(id, tab){
		
		if (tab == undefined) {
			this.navigate("//project/" + id + "/edit/overview", true);
			return false;
		}
		
		if (!this.project || this.project.get('id') !== id)
			this.init_project(id);
		
		if (this.projectEditView == undefined || this.previousRoute !== 'editProject' || this.projectEditView.model.get("id") !== id) {
			this.projectEditView = new ProjectEditView({model: this.project});
			$('#guts').html(this.projectEditView.el);
			this.projectEditView.currentTab = tab;
			this.project.fetch();
		}
		else {
			this.projectEditView.changeTab(tab);
		}
		
		if (this.previousRoute !== 'editProject')
			this.projectEditView.render();
	
	}

	
});