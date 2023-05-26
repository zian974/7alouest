<?php
use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
?>

<?php echo HTMLHelper::_(
	'bootstrap.renderModal',
	'modal-box', // selector
	array( // options
		'modal-dialog-scrollable' => true,
		'title'  => 'Edition - numéro de licence',
		'footer' => '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="modal-save">Send</button>',
	),
    '   <form action=""
              method="post" enctype="multipart/form-data" name="licence-form" id="licenceForm" class="p-4">'
    .'      <div class="control-group">
                <div class="control-label">
                    <label id="jform_licence_num-lbl" form="jform_licence_num">
                        Numéro de licence
                    </label>
                </div>
                <div class="controls">
                    <input type="text" name="jform[licence_num]" id="jform_licence_num" value="" class="form-control" aria-invalid="false">
                </div>
            </div>'
    .'      <p class="message alert alert-danger d-none"></p>'
    .'      <input type="hidden" name="jform[id]" id="jform_id" value="" />'
    . HTMLHelper::_('form.token')
    .'  </form>
    '
); ?>

<script type="text/javascript">
    (
        ( document, Joomla  ) => {
            
            let modalbox = document.getElementById("modal-box");
           
            if ( !modalbox ) {
                alert('Error modal');
                return;
            }
            
            let modalMessage = modalbox.querySelector('p.message');
            let modalSave = modalbox.querySelector('#modal-save');
            let modalForm = document.forms.licenceForm;
                        
            modalSave.addEventListener( 'click', e => {
                e.preventDefault();
                let formData = new FormData(modalForm);
                getRequest( JSON.stringify( Object.fromEntries(formData) ) );
            });
                
                
            modalbox && modalbox.addEventListener('hidden.bs.modal', event => {
                modalMessage.textContent = "Erreur lors de la sauvegarde";
                if ( !modalMessage.classList.contains('d-none') )
                    modalMessage.classList.toggle('d-none');
                modalForm.elements.jform_licence_num.value = null;
                modalForm.elements.jform_id.value = null;
            });
            
            modalbox && modalbox.addEventListener('show.bs.modal', function (event) {
                                
                // Button that triggered the modal
                let button = event.relatedTarget;
                
                // Extract info from data-bs-* attributes
                let numLicence = button.getAttribute('data-licence-num');
                let stagiaireId = button.getAttribute('data-id');
                                
                modalForm.elements.jform_licence_num.value = numLicence;
                modalForm.elements.jform_id.value = stagiaireId;
                
                
                getRequest = async (data) => {
                    let post;
                    let url = 'index.php?option=com_stages&format=json&task=stagiaire.saveX&label=licence';
                    
                    await fetch(url, {
                        method: 'POST',
                        body: data,
                        headers: {
                            'Content-Type': 'application/json',
                            'HTTP_X_CSRF_TOKEN': '<?php echo Session::getFormToken() ?>',
                        }
                    })
                        .then(res => {
                            return res.json();
                        })
                        .then( data => {
                            if ( data.success ) {
                                let spanNumLicence = document
                                    .querySelector('form[id="adminForm"] button[data-id="'+ stagiaireId +'"]')
                                    .parentNode.querySelector("span");
                                spanNumLicence.textContent = modalForm.elements.jform_licence_num.value;
                                button.setAttribute( 'data-licence-num', modalForm.elements.jform_licence_num.value );
                                modalbox.close();
                            }
                            else {
                                modalMessage.textContent = "Erreur lors de la sauvegarde";
                                modalMessage.classList.toggle('d-none')
                            }
                        })
                        .catch(err => {
                            console.log('Error: ', err)
                        });           
                };
                

                
            })
        }
    )( document, Joomla )




</script>