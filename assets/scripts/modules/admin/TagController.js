import $ from './../jquery';
import 'bootstrap';


class TagController {

    constructor() {

        this.selector = {
            frmTagCreate: '#frm-tag-create',
            frmTagEdit: '#frm-tag-edit'
        };
        
        this.init();

    }

    init() {
        this.createTooltip();
        this.submitFormTagCreate();
        this.submitFormTagEdit();
    }
    
    createTooltip() {
        $('[title]').tooltip();
    }

    submitFormTagCreate() {
        let $frmTagCreate = $(this.selector.frmTagCreate);
        if ($frmTagCreate.length > 0) {
            let $btnSubmit = $frmTagCreate.find('[type="submit"]');
            $frmTagCreate.on('submit', (e) => {
                $btnSubmit.toggleBtnSubmit(true);
            });
        }
    }
    
    submitFormTagEdit() {
        let $frmTagEdit = $(this.selector.frmTagEdit);
        if ($frmTagEdit.length > 0) {
            let $btnSubmit = $frmTagEdit.find('[type="submit"]');
            $frmTagEdit.on('submit', (e) => {
                $btnSubmit.toggleBtnSubmit(true);
            });
        }        
    }

}

export default TagController;