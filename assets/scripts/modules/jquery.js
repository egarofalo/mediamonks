import $ from 'jquery';

$.extend({
    sizeOf: function (obj) {
        var i = 0;
        for (var key in obj) {
            i++;
        }
        return i;
    }
});

$.fn.extend({
    serializeObject: function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    },
    toggleBtnSubmit: function (submit) {
        if (submit === true) {
            this.prop('disabled', true).children(':eq(0)').addClass('d-none').next().removeClass('d-none');
        } else {
            this.prop('disabled', false).children(':eq(0)').removeClass('d-none').next().addClass('d-none');
        }
    }
});

export default $;