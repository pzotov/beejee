	$(function(){
		$("#task-form").each(function (){
			var previewMode = false,
				$preview = $("#preview"),
				$name = $("#task-name"),
				$email = $("#task-email"),
				$text = $("#task-text"),
				$image = $("#task-image");
			$(".btn-save", this).on("click", function(){
				previewMode = false;
			});
			$(".btn-preview", this).on("click", function(){
				previewMode = true;
			});
			$(this).on("submit", function(e){
				if(previewMode){
					e.preventDefault();
					var imageData = '',
						preview;
					if($image.val()) {
						var reader = new FileReader();
						reader.onload = function (e) {
							$('#preview-image').attr('src', e.target.result);
						};
						imageData = '<img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" id="preview-image" style="max-width: 320px; max-height: 240px;" alt="" class="img-responsive">';
						reader.readAsDataURL($image[0].files[0]);
					} else if($image.data("current")){
						imageData = '<img src="' + $image.data("current") + '" id="preview-image" style="max-width: 320px; max-height: 240px;" alt="" class="img-responsive">';
					}
					preview = '<tr>' +
						'<td>' + $name.val() + '</td>' +
						'<td>' + ( $email.val() ? '<a href="mailto:' + $email.val() + '">' + $email.val() + '</a>' : '' ) + '</td>' +
						'<td>' + $text.val().replace(/\n/img, '<br>') + '</td>' +
						'<td>' + imageData + '</td>' +
						'</tr>';
					$preview.html(preview);
					$("#preview-modal").modal();
				}
			})
		});
	});
