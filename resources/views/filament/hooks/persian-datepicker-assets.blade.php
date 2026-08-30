<script src="{{asset('js/jquery-3.7.1.min.js')}}"></script>
<script>
    window.$ = window.jQuery = jQuery;
</script>
<script src="{{ asset('date/persian-date.min.js') }}"></script>
<script src="{{ asset('date/persianDatepicker.js') }}"></script>

<script>

    document.addEventListener("DOMContentLoaded", function () {

        const inputs = document.querySelectorAll('[data-persian-picker="true"]');
        console.log($.fn.persianDatepicker);
        inputs.forEach(function(input){

            if (input.dataset.pickerLoaded) return;

            input.dataset.pickerLoaded = true;

            $(input).persianDatepicker({
                format: 'YYYY/MM/DD HH:mm',
                timePicker: {
                    enabled: true,
                    meridian: false
                },
                initialValue: false,
                autoClose: true,
            });

        });

    });
</script>

