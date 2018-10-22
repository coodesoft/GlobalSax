(function($){

  var loadUserContentCallback = function(form, action, target, callback){
    var data = {
      'data': $(form).serialize(),
      'action': action,
    }
    $.post(ajaxurl, data, function(data){
        $(target).html(data);
        $('body').removeClass('gbs-progress');

        if (callback != undefined)
          callback(data);
    });
  }

  var loadVariationTable = function(id){
    var data = {
      'product_id': id,
      'action': 'gbs_load_variations',
    }
    $.post(ajaxurl, data, function(data){
        $('#gbs_variation_popup').addClass('active');
        $('#gbs_variation_popup .body').html(data);
        $('body').removeClass('gbs-progress');
    });
  }

  var sendContent = function(form, action, target, callback){
    var data = {
      'data': $(form).serialize(),
      'action': action,
    }
    $.post(ajaxurl, data, function(data){
      data = JSON.parse(data);

      $(target).html(data['msg']);
      $('body').removeClass('gbs-progress');
      if (callback != undefined)
        callback(data);
    });
  }

  $(document).ready(function(){
    let root = '#gbsCatalog';

    $(root).off().on('change', '#selectCategoryForm select', function(){
      $('body').addClass('gbs-progress');
      loadUserContentCallback(this, 'gbs_get_products_by_category', '#gbs_productos_list');
    });

    $(root).on('click', 'li.product', function(){
      $('body').addClass('gbs-progress');

      let id = $(this).data('product');
      loadVariationTable(id);
    });

    $(root).on('click', 'span.gbs-close', function(){
      $(this).closest('.gbs-dialog').removeClass('active');
      $(this).closest('.gbs-dialog').find('.body').empty();
    });

    $(root).on('submit', '#gbsAddVariationToCartForm', function(e){
      e.preventDefault();
      e.stopPropagation();
      $('body').addClass('gbs-progress');
      sendContent(this, 'gbs_add_variations_to_cart', '#gbs_action_result p.body', function(data){
        $('#gbs_action_result').addClass(data['type']);
        $('#gbs_action_result').addClass('active');
      });
    })
  });


})(jQuery);
