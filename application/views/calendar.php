<!DOCTYPE html>
<html>
	<head>
		<title>Aurora-QAlendar</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<!-- jQuery, Underscore, Backbone, Jquery UI -->
		<?= HTML::script('http://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js') . PHP_EOL; ?>
		<?= HTML::script('http://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.4/underscore-min.js') . PHP_EOL; ?>
		<?= HTML::script('http://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.0.0/backbone-min.js') . PHP_EOL; ?>
		<?= HTML::script('http://cdnjs.cloudflare.com/ajax/libs/backbone.modelbinder/0.1.6/Backbone.ModelBinder.min.js') . PHP_EOL; ?>
		<?= HTML::script('http://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js') . PHP_EOL; ?>
		<!-- Bootstrap -->
		<?= HTML::style('http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/css/bootstrap.min.css') . PHP_EOL; ?>
		<?= HTML::script('http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.1/js/bootstrap.min.js') . PHP_EOL; ?>
		<!-- Full Calendar -->
		<?= HTML::style('http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.1/fullcalendar.css') . PHP_EOL; ?>
		<?= HTML::script('http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.1/fullcalendar.js') . PHP_EOL; ?>

		<style>
			.fc-content {
				background-color: white;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<h1>Aurora-QAlendar</h1>
			<div class="row">
				<div id="calendar" class="well span12">

				</div>
			</div>
		</div>
		<div id='eventDialog' class='modal hide fade'>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">Manage Event <span class="label label-large" name="label"></span></h3>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<input name="id" type="hidden">
					<div class="control-group">
						<label class="control-label" for="inpLabel">Title</label>
						<div class="controls">
							<div class="row-fluid">
								<input type="text" id="inpTitle" placeholder="title" name="title" class="span12">
							</div>
						</div>
					</div>
				</form>

			</div>
			<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
				<button class="btn btn-primary">Save changes</button>
				<button class="btn btn-danger pull-left">Delete</button>
			</div>
		</div>
		<script>

			var Model_Calendar_Event = Backbone.Model.extend({
				toFullCalendarJSON: function() {
					var j = this.toJSON();
					j.id = this.cid;
					return j;
				}
			});
			var Collection_Calerndar_Event = Backbone.Collection.extend({
				model: Model_Calendar_Event,
				url: 'api/event',
				toFullCalendarJSON: function() {
					return this.map(function(model) {
						return model.toFullCalendarJSON();
					});
				},
			});
			var EventsView = Backbone.View.extend({
				initialize: function() {
					_.bindAll(this);
					this.eventView = new EventView();
					this.collection.bind('reset', this.reset);
					this.collection.bind('add', this.addOne);
					this.collection.bind('change', this.change);
					this.collection.bind('destroy', this.destroy);
				},
				render: function() {
					var self = this;
					this.$el.fullCalendar({
						header: {
							left: 'prev,next today',
							center: 'title',
							right: 'month,agendaWeek,agendaDay'
						},
						selectable: true,
						selectHelper: true,
						editable: true,
						ignoreTimezone: false,
						select: this.select,
						eventClick: this.eventClick,
						eventDrop: this.eventDrop,
						eventResize: this.eventResize,
						events: function(start, end, callback) {
							callback(self.collection.toFullCalendarJSON());
						}
					});
				},
				reset: function() {
					this.$el.fullCalendar('refetchEvents');
				},
				addOne: function(event) {
					this.$el.fullCalendar('renderEvent', event.toFullCalendarJSON());
				},
				select: function(startDate, endDate, allDay, jsEvent, view) {
					this.eventView.collection = this.collection;
					this.eventView.model = new this.collection.model({allDay: allDay, start: startDate, end: endDate});
					this.eventView.render();
				},
				eventClick: function(fcEvent) {
					this.eventView.model = this.collection.get(fcEvent.id);
					this.eventView.render();
				},
				change: function(event) {
					// Look up the underlying event in the calendar and update its details from the model
					var fcEvent = this.$el.fullCalendar('clientEvents', event.cid)[0];
					$.extend(fcEvent, event.toFullCalendarJSON());
					this.$el.fullCalendar('updateEvent', fcEvent);
				},
				eventDrop: function(fcEvent, dayDelta, minuteDelta, allDay, revertFunc) {
					// Lookup the model that has the ID of the event
					var model = this.collection.get(fcEvent.id);
					// and update its attributes
					model.set({
						start: fcEvent.start,
						end: fcEvent.end,
						allDay: allDay,
					});
					// save via REST
					model.save({}, {error: revertFunc})
				},
				eventResize: function(fcEvent, dayDelta, minuteDelta, revertFunc) {
					// Lookup the model that has the ID of the event
					var model = this.collection.get(fcEvent.id);
					// and update its attributes
					model.set({
						start: fcEvent.start,
						end: fcEvent.end,
					});
					// save via REST
					model.save({}, {error: revertFunc})
				},
				destroy: function(event) {
					this.$el.fullCalendar('removeEvents', event.cid);
				}
			});

			/*
			 * EventView: eventDialog
			 */
			var EventView = Backbone.View.extend({
				el: $('#eventDialog'),
				events: {
					'click .btn-primary': 'save',
					'click .btn-danger': 'destroy',
				},
				initialize: function() {
					_.bindAll(this);
					this.modelBinder = new Backbone.ModelBinder();
				},
				render: function() {
					this.bindModel();
					this.$el.modal();
					return this;
				},
				bindModel: function() {
					this.modelBinder.unbind();
					this.modelBinder.bind(this.model, this.$el);
				},
				save: function() {
					var self = this;
					if (this.model.isNew()) {
						this.collection.create(this.model, {success: self.close});
					} else {
						this.model.save({}, {success: self.close});
					}
				},
				close: function() {
					this.$el.modal('hide');
				},
				destroy: function() {
					this.model.destroy({success: this.close});
				}
			});
			$(function() {
				window.events = new Collection_Calerndar_Event();
				new EventsView({
					el: $("#calendar"),
					collection: window.events,
				}).render();
				window.events.fetch();
			});
		</script>
	</body>
</html>
