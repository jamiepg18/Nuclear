!function(t){"use strict";function i(t){this.el=t,this._init()}i.prototype={_init:function(){this.list=this.el.find("ul.taglist"),this.inputItem=this.el.find("div.tag-input"),this.input=this.inputItem.find('input[name="name"]'),this.results=this.el.find("ul.form-items-list-results"),this.parent=this.el.closest(".form-group"),this.urldetach=this.el.data("urldetach"),this.urladd=this.el.data("urladd"),this.urlsearch=this.el.data("urlsearch"),this.tags=[],this.tempTags=[],this.searching=!1,this._parseExistingTags(),this._initEvents()},_parseExistingTags:function(){var t=this.list.find(".tag"),i=this;t.each(function(){var t=$(this);i._addToTagList(t.data("id"),t.find("span").text().toLowerCase())})},_addToTagList:function(t,i){this.tags[t]=i},_initEvents:function(){var t=this;this.input.bind("keydown",function(i){var s=$(this),a=s.val().trim();return 27===i.which?(i.stopPropagation(),""===a?s.blur():s.val(""),void t._clearSearch()):9===i.which||13===i.which?(i.preventDefault(),void(""!==a&&(t._addTag(a),s.val("")))):(!t.searching&&a.length>0&&t._search(a),void(""==a&&t._clearSearch()))}),this.list.on("click",".icon-cancel",function(){t._detachTag($(this))}),this.results.on("click","li",function(){t._addTag($(this).text()),t._clearSearch()}),this.input.on("focus",function(){t._showResults()}),$("body").click(function(){t._hideResults()}),this.parent.click(function(t){t.stopPropagation()})},_detachTag:function(t){var i=t.closest(".tag"),s=i.data("id");if(!i.hasClass("disabled")){i.addClass("disabled");var a=this;$.ajax({type:"POST",url:a.urldetach,data:{_method:"DELETE",tag:s},success:function(t){a._removeTag(t.id)},error:function(){a._enableTag(s)}})}},_removeTag:function(t){var i=this.list.find('li[data-id="'+t+'"]');delete this.tags[t],i.remove(),this._setListClass()},_removeTempTag:function(t){var i=this.list.find('li[data-tempid="'+t+'"]');delete this.tempTags[t],i.remove(),this._setListClass()},_enableTag:function(t){this.list.find('li[data-id="'+t+'"]').removeClass("disabled")},_enableTempTag:function(t,i){var s=this.list.find('li[data-tempid="'+t+'"]');s.removeClass("disabled"),s.removeData("tempid"),s.attr({"data-id":i})},_addTag:function(t){var i=this.tags.indexOf(t.toLowerCase());i>-1?this._flashTag(i):(this.tempTags.push(t),i=this.tempTags.indexOf(t),this._createTag(i,t),this._linkTag(i,t),this._setListClass(),this._clearSearch())},_linkTag:function(t,i){var s=this;$.ajax({type:"POST",url:s.urladd,data:{_method:"PUT",name:i},success:function(i){s._tagExists(i.id)?(s._removeTempTag(t),s._flashTag(i.id)):(s._enableTempTag(t,i.id),s._addToTagList(i.id,i.name),delete s.tempTags[t]),s._clearSearch(),s._setListClass()},error:function(){s._removeTempTag(t),s._setListClass()}})},_tagExists:function(t){return"undefined"!=typeof this.tags[t]?!0:!1},_createTag:function(t,i){$('<li class="tag disabled" data-id="" data-tempid="'+t+'">'+html_entities(i)+'<i class="icon-cancel"></i></li>').appendTo(this.list)},_flashTag:function(t){var i=this.list.find('li[data-id="'+t+'"]');i.addClass("flash"),setTimeout(function(){i.removeClass("flash")},100)},_setListClass:function(){0==count(this.tags)?this.list.addClass("empty"):this.list.removeClass("empty")},_search:function(t){var i=this;i.searching||$.post(this.urlsearch,{q:t},function(t){i._populateResults(t)})},_populateResults:function(t){if(""!=this.input.val().trim()){this.results.empty();for(var i in t)if(!this._tagExists(i)){var s=this._createListItem(i,t[i]);this.results.append(s)}}},_createListItem:function(t,i){return $('<li data-id="'+t+'">'+i+"</li>")},_showResults:function(){this.results.removeClass("hidden")},_hideResults:function(){this.results.addClass("hidden")},_clearSearch:function(){this.results.empty(),this.input.val("")}},t.Tag=i}(window),$(".form-group-tag").each(function(){new Tag($(this))}),$(".form-group input").focus(function(){$(this).closest(".form-group").addClass("focus")}),$(".form-group input").blur(function(){$(this).closest(".form-group").removeClass("focus")});