Komento.module('komento.commentlist', function($) {
var module = this;

Komento.require()
	.library('ui/effect-highlight', 'ui/effect-fade', 'fancybox')
	.image(Komento.options.spinner)
	.script('sharelinks', 'markitup', 'komento.common', 'komento.commentitem', 'komento.bbcode')
	.done(function() {
		Komento.Controller(
			'CommentList',
			{
				defaults: {
					'{commentList}': '.kmt-list',
					'{commentItem}': '.kmt-item',
					'{noComment}': '.kmt-empty-comment',
					'{loadMore}': '.loadMore',
					// Comment Item
					'{commentText}'			: '.commentText',
					'{commentInfo}'			: '.commentInfo',
					'{commentForm}'			: '.commentForm',
					'{deleteButton}'		: '.deleteButton',
					'{editButton}'			: '.editButton',
					'{saveEditButton}'		: '.saveEditButton',
					'{cancelEditButton}'	: '.cancelEditButton',
					'{editForm}'			: '.editForm',
					'{editInput}'			: '.editInput',
					'{replyButton}'			: '.replyButton',
					'{shareBox}'			: '.shareBox',
					'{reportButton}'		: '.reportButton',
					'{statusButton}'		: '.statusButton',
					'{statusOptions}'		: '.statusOptions',
					'{publishButton}'		: '.publishButton',
					'{unpublishButton}'		: '.unpublishButton',
					'{stickButton}'			: '.stickButton',
					'{likeButton}'			: '.likeButton',
					'{likesCounter}'		: '.likesCounter',
					'{parentLink}'			: '.parentLink',
					'{parentContainer}'		: '.parentContainer',
					'{socialButton}'		: '.socialButton',
					'{attachmentWrap}'		: '.attachmentWrap',
					'{attachmentList}'		: '.attachmentList',
					'{attachmentFile}'		: '.attachmentFile',
					'{attachmentDelete}'	: '.attachmentDelete',
					'{attachmentImage}'		: '.attachmentImage',
					'{attachmentImageLink}'	: '.attachmentImageLink',

					view: {
						editForm: 'comment/item/edit.form',
						deleteSingle: 'dialogs/delete.single',
						deleteChilds: 'dialogs/delete.affectchild',
						publishDialog: 'dialogs/publish.affectchild',
						unpublishDialog: 'dialogs/unpublish.affectchild',
						deleteAttachment: 'dialogs/delete.attachment'
					}
				}
			},
			function(self)
			{ return {
				init: function() {
					if(self.noComment().length == 0) {

						// initialise all sharelinks
						self.generateSharelinks();

						// try to get if permalink is parsed in
						if(Komento.options.konfig.enable_ajax_permalink == 1) {
							var url = document.location.href.split('#');
							var commentid;
							for(var i = 0; i < url.length; i++) {
								if(url[i].substring(0, 4) == 'kmt-') {
									commentid = url[i];
									break;
								}
							}

							if(commentid) {
								self.finditem(commentid);
							}

							if(Komento.options.config.upload_image_fancybox == 1) {
								self.attachmentImageLink().fancybox({
									type: 'image',
									helpers: {
										overlay: Komento.options.config.upload_image_overlay == 1 ? true : false
									}
								});
							}
						}

						// execute SH
						if(Komento.options.config.enable_syntax_highlighting == 1) {
							$.loadSHBrushes();
						}
					}
				},

				generateSharelinks: function() {
					var callback = function() {
						self.socialButton().each(function(i, el) {
							$(el).sharelinks();
						});
					};

					self.generateShortLinks(callback);
				},

				generateShortLinks: function(callback) {
					var replaceUrl = function(url) {
						self.socialButton().each(function(index, element) {
							if( !$(element).attr('loaded') ) {
								var commentid = $(element).attr('commentid');
								var permalink = url + '#kmt' + commentid;

								$(element).attr('url', permalink);
								$(element).parents('.kmt-share-balloon').find('.short-url').val(permalink);
							}
						});

						callback && callback();
					};

					if(!Komento.shortenLink) {
						$.shortenlink(Komento.contentLink, replaceUrl);
					} else {
						replaceUrl(Komento.shortenLink);
					}
				},

				finditem: function(commentid)
				{
					if($('#' + commentid).length == 0) {
						if(!(self.loadMore().is(':hidden') || self.loadMore().length == 0)) {
							self.loadMoreComments(function() {
								self.finditem(commentid)
							});
						}
					} else {
						$('#' + commentid).scroll();
						$('#' + commentid).highlight();
					}
				},

				'{loadMore} click': function(el) {
					if(el.checkClick()) {
						el.loading();

						self.loadMoreComments();
					}
				},

				addComment: function(nodeId, html, contentLink) {
					var commentHtml = $(html);
					var commentId = commentHtml.attr('id');
					var implement = 0;

					if(self.noComment().length != 0) {
						self.noComment().remove();
						self.commentList().append(commentHtml);
						implement = 1;
					} else {
						if(nodeId == 0 || Komento.options.config.enable_threaded == 0) {
							if(Komento.options.config.load_previous == 1) {
								if(Komento.sort == 'oldest') {
									self.commentList().append(commentHtml);
									implement = 1;
								}

								if(Komento.sort == 'latest' && (self.loadMore().length == 0 || self.loadMore().is(':hidden'))) {
									self.commentList().prepend(commentHtml);
									implement = 1;
								}
							} else {
								if(Komento.sort == 'oldest' && (self.loadMore().length == 0 || self.loadMore().is(':hidden'))) {
									self.commentList().append(commentHtml);
									implement = 1;
								}

								if(Komento.sort == 'latest') {
									self.commentList().prepend(commentHtml);
									implement = 1;
								}
							}
						} else {
							if(Komento.sort == 'oldest') {
								if(self.commentList().find('#kmt-' + nodeId).next().attr('id') == 'kmt-form') {
									self.commentList().find('#kmt-' + nodeId).next().after(commentHtml);
									implement = 1;
								} else {
									self.commentList().find('#kmt-' + nodeId).after(commentHtml);
									implement = 1;
								}
							}

							if(Komento.sort == 'latest') {
								var parent = self.commentList().find('#' + commentHtml.attr('parentid'));

								if(parent.attr('id') === 'kmt-' + nodeId) {
									if(self.commentList().find('#kmt-' + nodeId).next().attr('id') == 'kmt-form') {
										self.commentList().find('#kmt-' + nodeId).next().after(commentHtml);
									} else {
										self.commentList().find('#kmt-' + nodeId).after(commentHtml);
									}
								} else {
									self.commentList().find('#kmt-' + nodeId).before(commentHtml);
								}

								implement = 1;
							}
						}
					}

					if(implement) {
						Komento.loadedCount++;

						if(nodeId != 0) {
							self.addChildCount($('#' + commentId).attr('parentid'));
						}

						self.generateSharelinks();

						if(Komento.options.config.scroll_to_comment == 1) {
							$('#' + commentId).highlight().scroll();
						}

						if(Komento.options.config.enable_syntax_highlighting == 1) {
							$.loadSHBrushes();
						}
					}

					self.kmt.tools.addCommentCounter();

					Komento.totalCount++;
				},

				addChildCount: function(parentid) {
					var count = parseInt($('.' + parentid).attr('childs'));
					$('.' + parentid).attr('childs', count + 1);

					if($('.' + parentid).exists() && parseInt($('li.' + parentid).attr('parentid').split('-')[1]) > 0) {
						self.addChildCount($('.' + parentid).attr('parentid'));
					}
				},

				loadMoreComments: function(callback) {
					var startCount;
					var limit = parseInt(Komento.options.config.max_comments_per_page);

					if(Komento.options.config.load_previous == 1) {
						startCount = Komento.totalCount - Komento.loadedCount - limit;

						if(startCount < 0) {
							limit = limit + startCount;
							startCount = 0;
						}
					} else {
						startCount = Komento.loadedCount;
					}

					Komento.ajax('site.views.komento.loadmorecomments', {
						component: Komento.component,
						cid: Komento.cid,
						start: startCount,
						limit: limit,
						sort: Komento.sort,
						contentLink: Komento.contentLink,
					},
					{
						success: function(html, loadedCount, loadMore) {
							var list = $(html).filter('li');

							if(Komento.options.config.load_previous == 1) {
								self.commentList().prepend(list);
							}
							else {
								self.commentList().append(list);
							}

							list.highlight();

							Komento.loadedCount = loadedCount;

							self.loadMore().removeClass('loading');

							if(loadMore == 1) {
								self.loadMore().removeClass('disabled');

								var nextStartCount;
								if(Komento.options.config.load_previous == 1) {
									nextStartCount = Komento.totalCount - Komento.loadedCount - limit;

									if(nextStartCount < 0) {
										limit = limit + nextStartCount;
										nextStartCount = 0;
									}
								} else {
									nextStartCount = Komento.loadedCount;
								}
								self.loadMore().attr('href', '#!kmt-start=' + nextStartCount);
							} else {
								self.loadMore().hide();
							}

							callback && callback();
						},

						fail: function(limit, limitstart, sort) {
						}
					});
				},

				/************** Comment Items **************/

				set: function(el) {
					self.item = el.itemset(self.options);
				},

				'{deleteButton} click': function(el, event) {
					// propagation hack to solve reply form issues sharing the same function name with famelist.js
					event.stopPropagation();

					self.set(el);
					self.showDeleteDialog();
				},

				'{editButton} click': function(el, event) {
					// propagation hack to solve reply form issues sharing the same function name with famelist.js
					event.stopPropagation();

					self.set(el);
					if(el.checkClick()) {
						el.loading();

						if(el.checkSwitch()) {
							self.edit(el);
						} else {
							self.cancelEdit(el);
						}
					}
				},

				'{saveEditButton} click': function(el, event) {
					// propagation hack to solve reply form issues sharing the same function name with famelist.js
					event.stopPropagation();

					self.set(el);
					self.saveEdit(el);
				},

				'{cancelEditButton} click' : function(el, event) {
					// propagation hack to solve reply form issues sharing the same function name with famelist.js
					event.stopPropagation();

					self.set(el);
					self.cancelEdit(el);
				},

				'{replyButton} click': function(el, event) {
					// propagation hack to solve reply form issues sharing the same function name with famelist.js
					event.stopPropagation();

					self.set(el);
					if(Komento.options.konfig.enable_inline_reply == 1) {
						if(el.checkSwitch()) {
							self.reply();
						} else {
							self.cancelReply();
						}
					} else {
						self.kmt.form.staticReply(self.item);
					}
				},

				'{reportButton} click': function(el, event) {
					// propagation hack to solve reply form issues sharing the same function name with famelist.js
					event.stopPropagation();

					self.set(el);
					if(el.checkClick()) {
						el.loading();
						if(el.checkSwitch()) {
							self.reportComment(el);
						} else {
							self.cancelreportComment(el);
						}
					}
				},

				'{publishButton} click': function(el, event) {
					// propagation hack to solve reply form issues sharing the same function name with famelist.js
					event.stopPropagation();

					self.set(el);
					if(self.item.childs > 0) {
						self.showPublishDialog(el);
					} else {
						self.publishComment(el);
					}
				},

				'{unpublishButton} click': function(el, event) {
					// propagation hack to solve reply form issues sharing the same function name with famelist.js
					event.stopPropagation();

					self.set(el);
					if(self.item.childs > 0) {
						self.showUnpublishDialog(el);
					} else {
						self.unpublishComment(el);
					}
				},

				'{stickButton} click': function(el, event) {
					// propagation hack to solve reply form issues sharing the same function name with famelist.js
					event.stopPropagation();

					self.set(el);
					if(el.checkClick()) {
						el.loading();

						if(el.checkSwitch()) {
							self.stick(el);
						} else {
							self.unstick(el);
						}
					}
				},

				'{likesCounter} click': function(el, event) {
					// propagation hack to solve reply form issues sharing the same function name with famelist.js
					event.stopPropagation();

					self.set(el);

					self.showLikesDialog(el);
				},

				'{likeButton} click': function(el, event) {
					// propagation hack to solve reply form issues sharing the same function name with famelist.js
					event.stopPropagation();

					self.set(el);
					if(el.checkClick()) {
						el.find('span').loading();

						if(el.checkSwitch()) {
							self.like(el);
						} else {
							self.unlike(el);
						}
					}
				},

				'{parentLink} mouseover': function(el) {
					self.set(el);
					if(Komento.options.config.enable_threaded == 1) {
						$('#' + self.item.parentid).addClass('kmt-highlight');
					} else {
						self.item.element.mine.parentContainer.show();
						if(self.item.element.mine.parentContainer.attr('loaded') == 0) {
							self.item.element.mine.parentContainer.html('<img src="' + Komento.options.spinner + '" />');
							self.loadParent();
						}
					}
				},

				'{parentLink} mouseout': function(el) {
					self.set(el);
					if(Komento.options.config.enable_threaded == 1) {
						$('#' + self.item.parentid).removeClass('kmt-highlight');
					} else {
						self.item.element.mine.parentContainer.hide();
					}
				},

				'{parentLink} click': function(el) {
					self.set(el);
					var parent = $('.' + self.item.parentid);
					parent.highlight();
				},

				'{attachmentDelete} click': function(el, event) {
					// propagation hack to solve reply form issues sharing the same function name with famelist.js
					event.stopPropagation();

					self.set(el);

					self.showAttachmentDeleteDialog(el);
				},

				'{attachmentImage} click': function(el, event) {

				},

				closeDialog: function() {
					if($('.foundryDialog').length > 0) {
						$('.foundryDialog').controller().close();
					}
				},

				edit: function() {
					var editWrap = self.item.mine.find('#' + self.item.commentid + '-edit');
					if(editWrap.length == 0) {
						Komento.ajax('site.views.komento.getcommentraw', {
							id: self.item.id
						},
						{
							success: function(comment) {
								self.item.element.mine.editButton
									.text($.language('COM_KOMENTO_COMMENT_EDIT_CANCEL'))
									.switchOff()
									.doneLoading()
									.enable();

								var editForm = self.view.editForm({commentId: self.item.commentid, commentText: comment});
								self.item.element.mine.commentText.after(editForm);

								// set again for newly generated editform and editinput because previously set, dom for editform and editinput not yet created
								self.item.element.mine.editForm = $(editForm);
								self.item.element.mine.editInput = self.item.element.mine.editForm.find('.editInput');
								self.item.mine.data('item', self.item);

								self.item.element.mine.editInput.markItUp($.getBBcodeSettings());
							},

							fail: function(message) {
								self.item.element.mine.editButton
									.text($.language('COM_KOMENTO_ERROR'))
									.doneLoading();
							}
						});
					} else {
						editWrap.show();

						self.item.element.mine.editButton
							.text($.language('COM_KOMENTO_COMMENT_EDIT_CANCEL'))
							.switchOff()
							.doneLoading()
							.enable();
					}

					// bugged
					// .after insert doesnt register at first
					// $('#' + commentId + '-edit').find('textarea').focus();
				},

				cancelEdit: function() {
					self.item.element.mine.editButton
						.text($.language('COM_KOMENTO_COMMENT_EDIT'))
						.switchOn()
						.doneLoading()
						.enable();
					self.item.element.mine.editForm.hide();
				},

				saveEdit: function() {
					Komento.ajax('site.views.komento.editcomment', {
						id: self.item.id,
						edittedComment: self.item.element.mine.editInput.val()
					},
					{
						success: function(modified_html, modified_by, modified) {
							self.item.element.both.commentText.html(modified_html);

							if(Komento.options.config.enable_info == 1) {
								self.item.element.both.commentInfo.text($.language('COM_KOMENTO_COMMENT_EDITTED_BY', modified_by, modified)).show().css('display', '');
							}

							if(Komento.options.config.enable_syntax_highlighting == 1) {
								$.loadSHBrushes();
							}

							self.cancelEdit();
						},

						fail: function(message) {
							$.dialog({
								content: message
							});
						}
					});
				},

				reply: function() {
					$('.formAlert').hide().text('');;

					// only allow 1 reply at a time
					// revert all other cancelReplyButton upon clicking reply
					$(self.options['{replyButton}'])
						.switchOn()
						.find('span')
						.text($.language('COM_KOMENTO_COMMENT_REPLY'));

					self.item.element.mine.replyButton
						.switchOff()
						.find('span')
						.text($.language('COM_KOMENTO_COMMENT_REPLY_CANCEL'));

					self.kmt.form.reply(self.item);
				},

				cancelReply: function() {
					$(self.options['{replyButton}'])
						.switchOn()
						.find('span')
						.text($.language('COM_KOMENTO_COMMENT_REPLY'));

					self.kmt.form && self.kmt.form.cancelReply();
				},

				showDeleteDialog: function() {
					if(self.item.childs > 0) {
						$.dialog({
							content: self.view.deleteChilds(true, {childs: self.item.childs}),
							afterShow: function() {
								$('.foundryDialog').find('.delete-affectChild').click(function() {
									self.deleteComment(1);
								});

								$('.foundryDialog').find('.delete-moveChild').click(function() {
									self.deleteComment(0);
								});
							}
						});
					} else {
						$.dialog({
							content: self.view.deleteSingle(true),
							afterShow: function() {
								$('.foundryDialog').find('.delete-affectChild').click(function() {
									self.deleteComment(1);
								});
							}
						});
					}
				},

				deleteComment: function(affectChild) {
					$('.foundryDialog').find('.kmt-delete-status').show();

					Komento.ajax('site.views.komento.deletecomment', {
						id: self.item.id,
						affectChild: affectChild
					},
					{
						success: function() {
							self.closeDialog();

							if(affectChild == 1) {
								self.deleteChild(self.item.commentid);
							} else {
								self.moveChildUp(self.item.commentid, self.item.parentid);
							}

							self.item.both.hide('fade', function() {
								self.item.both.remove();
							});

							self.kmt.tools.deductCommentCounter();
						},

						fail: function() {
							$('.foundryDialog').find('.kmt-delete-status').text($.language('COM_KOMENTO_ERROR'));
						}
					});
				},

				deleteChild: function(id) {
					$('li[parentid="' + id + '"]').each(function() {

						// recursive function to delete other childs
						self.deleteChild($(this).attr('id'));
					}).hide('fade', function() {
						$(this).remove();
					});

					var count = $('li[parentid="' + id + '"]').length;
					self.kmt.tools.deductCommentCounter(count);
				},

				moveChildUp: function(id, parentid) {
					$('li[parentid="' + id + '"]').attr('parentid', parentid).each(function() {

						// move child class up 1 level
						$(this).removeClass('kmt-child-' + $(this).attr('depth')).addClass('kmt-child-' + ($(this).attr('depth') - 1));

						// move depth up 1 level
						$(this).attr('depth', ($(this).attr('depth') - 1));

						// recursive function to move other childs
						self.moveChildUp($(this).attr('id'));
					});
				},

				reportComment: function() {
					Komento.ajax('site.views.komento.action', {
						id: self.item.id,
						type: 'report',
						action: 'add'
					},
					{
						success: function() {
							self.item.element.both.reportButton
								.text($.language('COM_KOMENTO_COMMENT_REPORTED'))
								.switchOff()
								.doneLoading()
								.enable();
						},

						fail: function() {
							self.item.element.both.reportButton
								.text($.language('COM_KOMENTO_ERROR'));
						}
					});
				},

				cancelreportComment: function() {
					Komento.ajax('site.views.komento.action', {
						id: self.item.id,
						type: 'report',
						action: 'remove'
					},
					{
						success: function() {
							self.item.element.both.reportButton
								.text($.language('COM_KOMENTO_COMMENT_REPORT'))
								.switchOn()
								.doneLoading()
								.enable();
						},

						fail: function() {
							self.item.element.both.reportButton
								.text($.language('COM_KOMENTO_ERROR'));
						}
					});
				},

				showUnpublishDialog: function() {
					$.dialog({
						content: self.view.unpublishDialog(true, {childs: self.item.childs}),
						afterShow: function() {
							$('.foundryDialog').find('.unpublish-affectChild').click(function() {
								self.unpublishComment();
							});
						}
					});
				},

				unpublishComment: function() {
					Komento.ajax('site.views.komento.unpublish', {
						id: self.item.id
					},
					{
						success: function() {
							self.closeDialog();
							self.unpublishChild(self.item.commentid);
							self.item.both.hide('fade', function() {
								self.item.both.remove();
							});
							self.kmt.tools.deductCommentCounter();
						},

						fail: function() {

						}
					});
				},

				unpublishChild: function(id) {
					$('li[parentid="' + id + '"]').each(function() {
						self.unpublishChild($(this).attr('id'));
					}).hide('fade', function() {
						$(this).remove();
					});

					var count = $('li[parentid="' + id + '"]').length;
					self.kmt.tools.deductCommentCounter(count);
				},

				showPublishDialog: function() {

				},

				publishComment: function() {

				},

				publishChild: function(id) {

				},

				stick: function(el) {
					Komento.ajax('site.views.komento.stick', {
						id: self.item.id
					},
					{
						success: function() {
							self.item.element.mine.stickButton
								.text($.language('COM_KOMENTO_COMMENT_UNSTICK'))
								.switchOff()
								.doneLoading()
								.enable();

							self.item.mine.addClass('kmt-sticked');

							// append comment into stick list
							self.kmt.famelist.stickComment(self.item.mine.clone())
						},

						fail: function() {
							self.item.element.mine.stickButton
								.text($.language('COM_KOMENTO_ERROR'));
						}
					});
				},

				unstick: function() {
					Komento.ajax('site.views.komento.unstick', {
						id: self.item.id
					},
					{
						success: function() {
							self.item.element.both.stickButton
								.text($.language('COM_KOMENTO_COMMENT_STICK'))
								.switchOn()
								.doneLoading()
								.enable();

							self.item.both.removeClass('kmt-sticked');

							// remove comment from stick list
							self.kmt.famelist.unstickComment(self.item.commentid);
						},

						fail: function() {
							self.stickButton
								.text($.language('COM_KOMENTO_ERROR'));
						}
					});
				},

				like: function() {
					Komento.ajax('site.views.komento.action', {
						id: self.item.id,
						type: 'likes',
						action: 'add'
					},
					{
						success: function() {
							self.item.element.both.likeButton
								.switchOff()
								.enable()
								.find('span')
								.doneLoading()
								.text($.language('COM_KOMENTO_COMMENT_UNLIKE'));

							var likes = parseInt(self.item.element.mine.likesCounter.find('span').text()) + 1;
							self.item.element.both.likesCounter.find('span')
								.text(likes);
						},

						fail: function( message ) {
							self.item.element.both.likesCounter.find('span')
								.doneLoading()
								.text( message );
								// .text($.language('COM_KOMENTO_ERROR'));
						}
					});
				},

				unlike: function() {
					Komento.ajax('site.views.komento.action', {
						id: self.item.id,
						type: 'likes',
						action: 'remove'
					},
					{
						success: function() {
							self.item.element.both.likeButton
								.switchOn()
								.enable()
								.find('span')
								.doneLoading()
								.text($.language('COM_KOMENTO_COMMENT_LIKE'));

							var likes = parseInt(self.item.element.mine.likesCounter.find('span').text()) - 1;
							self.item.element.both.likesCounter.find('span')
								.text(likes);
						},

						fail: function() {
							self.item.element.both.likesCounter.find('span')
								.doneLoading()
								.text($.language('COM_KOMENTO_ERROR'))
						}
					});
				},

				showLikesDialog: function(el) {
					Komento.ajax('site.views.komento.getLikedUsers', {
						id: self.item.id
					},
					{
						success: function(html) {
							$.dialog({
								title: $.language('COM_KOMENTO_COMMENT_PEOPLE_WHO_LIKED_THIS'),
								content: html
							});
						}
					});
				},

				loadParent: function() {
					var parent = $('#' + self.item.parentid);
					if(parent.length != 0) {
						var avatar = parent.find('.kmt-avatar:not(.parentContainer > .kmt-avatar)').clone();
						var author = parent.find('.kmt-author:not(.parentContainer > .kmt-author)').clone();
						var time = parent.find('.kmt-time:not(.parentContainer > .kmt-time)').clone();
						var text = parent.find('.commentText:not(.parentContainer > .commentText)').clone();

						// todo: configurable
						self.item.element.both.parentContainer.html('')
							.append(avatar)
							.append(author)
							.append(time)
							.append(text);
					} else {
						var parentid = self.item.parentid.split('-')[1];

						Komento.ajax('site.views.komento.getcomment', {
							id: parentid
						},
						{
							success: function(html) {
								self.item.element.both.parentContainer.html(html);
							},

							fail: function() {

							}
						})
					}
					self.item.element.both.parentContainer.attr('loaded', 1);
				},

				showAttachmentDeleteDialog: function(el) {
					var attachmentid = $(el).parents('.attachmentFile').attr('attachmentid');
					var attachmentname = $(el).parents('.attachmentFile').attr('attachmentname');

					$.dialog({
						content: self.view.deleteAttachment(true, {attachmentname: attachmentname}),
						afterShow: function() {
							$('.foundryDialog').find('.delete-attachment').click(function() {
								self.closeDialog();
								self.deleteFile(attachmentid);
							});

							$('.foundryDialog').find('.delete-attachment-cancel').click(function() {
								self.closeDialog();
							});
						}
					});
				},

				deleteFile: function(attachmentid) {
					var commentid = self.item.id;

					Komento.ajax('site.views.komento.deleteAttachment', {
						id: commentid,
						attachmentid: attachmentid
					},
					{
						success: function() {
							self.item.element.both.attachmentFile.filter('.file-' + attachmentid).remove();

							if(self.item.mine.find('.attachmentFile').length == 0) {
								self.item.element.both.attachmentWrap.remove();
							}
						},

						fail: function(error) {
							alert('error deleting attachment at ' + error);
						}
					})
				}
			} }
		);
		module.resolve();
	});
});
