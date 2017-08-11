(function($){

$(document).ready(function(){
	
 $('#video-modal').on('show.bs.modal', function(e){
  var $link = $(e.relatedTarget),
      iframe = $link.data('iframe'),
			desc = $link.next('.omni-description').text(),
      $iframe = $('<iframe src="'+ iframe +'?wmode=transparent" width="600" height="300"></iframe>'+ '<div class="omnivideo-popup-description">'+desc+'</div>'),
      title = $link.parent().next('.gallery-caption').text();
  
  $(e.currentTarget)
    .find('.modal-body').html( $iframe ).end()
    .find('.modal-header h3').html( title );

});

$('#video-modal').on('hidden.bs.modal', function(e){
  $(e.currentTarget).find('.modal-body').empty();
});

});
})(jQuery);