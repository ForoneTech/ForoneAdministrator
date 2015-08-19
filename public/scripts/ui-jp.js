+function ($) {

  $(function(){

      $("[ui-jp]").each(function(){
        var self = $(this);
        var options = eval('[' + self.attr('ui-options') + ']');

        if ($.isPlainObject(options[0])) {
          options[0] = $.extend({}, options[0]);
        }

        uiLoad.load(jp_config[self.attr('ui-jp')]).then( function(){
          self[self.attr('ui-jp')].apply(self, options);
        });
      });

  });
}(jQuery);