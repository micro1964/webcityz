Komento.module('admin.database', function($) {
	var module = this;

	Komento
	.require()
	.language(
		'COM_KOMENTO_SETTINGS_DATABASE_POPULATE_DEPTH_STATUS_UPDATING',
		'COM_KOMENTO_SETTINGS_DATABASE_POPULATE_DEPTH_STATUS_COMPLETED',
		'COM_KOMENTO_SETTINGS_DATABASE_POPULATE_DEPTH_STATUS_DONE'
	)
	.done(function($) {
		Komento.Controller('Database.DepthMaintenance', {
			defaultOptions: {
				'{start}'			: '.start',
				'{status}'			: '.status',
				'{total}'			: '.total',
				'{count}'			: '.count',

				'{statusWrapper}'	: '.statusWrapper',
				'{totalWrapper}'	: '.totalWrapper',
				'{countWrapper}'	: '.countWrapper'
			}
		}, function(self) {
			return {
				init: function() {
					self.counter = 0;
				},

				'{start} click': function(el) {
					if(el.enabled()) {
						el.disabled(true);

						self.statusWrapper().show();
						self.totalWrapper().show();
						self.countWrapper().show();

						self.status().html( '<img src="' + Komento.options.spinner + '" />' + $.language('COM_KOMENTO_SETTINGS_DATABASE_POPULATE_DEPTH_STATUS_UPDATING'));
						self.totalArticle(0);
						self.countArticle(0);

						Komento.ajax('admin.views.system.getArticleStatistics').done(function(articles) {
							self.totalArticle(articles.length);

							self.articles = articles;
							self.populateDepth();
						}).fail(function() {
							self.status().html($.language('COM_KOMENTO_SETTINGS_DATABASE_POPULATE_DEPTH_STATUS_ERROR'));
						});
					}
				},

				populateDepth: function() {
					var data = self.articles[self.counter];

					if(data === undefined) {
						self.populateComplete();
						return;
					}

					Komento.ajax('admin.views.system.populateDepth', {
						component: data.component,
						cid: data.cid
					}).done(function(count) {
						self.counter++;

						self.countArticle(parseInt(self.countArticle()) + 1);

						self.populateDepth();
					}).fail(function() {
						self.status().html($.language('COM_KOMENTO_SETTINGS_DATABASE_POPULATE_DEPTH_STATUS_ERROR'));
					});
				},

				totalArticle: function(total) {
					return total === undefined? self.total().html() : self.total().html(total);
				},

				countArticle: function(count) {
					return count === undefined ? self.count().html() : self.count().html(count);
				},

				populateComplete: function() {
					self.status().html($.language('COM_KOMENTO_SETTINGS_DATABASE_POPULATE_DEPTH_STATUS_COMPLETED'));
					self.start().enabled(true);
				}
			}
		});

		module.resolve();
	});
});
