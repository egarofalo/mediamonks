import $ from './../jquery';
import 'bootstrap';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';


class PostController {

    constructor() {

        this.selector = {
            textareaPostBody: '#post_body',
            frmPostCreate: '#frm-post-create',
            frmPostEdit: '#frm-post-edit'
        };

        this.init();

    }

    init() {
        this.createTooltip();
        this.createBodyEditor();
        this.submitFormPostCreate();
        this.submitFormPostEdit();
    }

    createTooltip() {
        $('[title]').tooltip();
    }

    createBodyEditor() {
        let $postBody = $(this.selector.textareaPostBody);
        if ($postBody.length > 0) {
            ClassicEditor.create(document.querySelector(this.selector.textareaPostBody), {
                toolbar: ['heading', 'bold', 'italic', 'bulletedList', 'numberedList', 'link'],
                removePlugins: ['BlockQuote', 'ImageUpload']
            });
        }
    }

    submitFormPostCreate() {
        let $frmPostCreate = $(this.selector.frmPostCreate);
        if ($frmPostCreate.length > 0) {
            let $btnSubmit = $frmPostCreate.find('[type="submit"]');
            $frmPostCreate.on('submit', (e) => {
                $btnSubmit.toggleBtnSubmit(true);
            });
        }
    }
    
    submitFormPostEdit() {
        let $frmPostEdit = $(this.selector.frmPostEdit);
        if ($frmPostEdit.length > 0) {
            let $btnSubmit = $frmPostEdit.find('[type="submit"]');
            $frmPostEdit.on('submit', (e) => {
                $btnSubmit.toggleBtnSubmit(true);
            });
        }        
    }    

}

export default PostController;