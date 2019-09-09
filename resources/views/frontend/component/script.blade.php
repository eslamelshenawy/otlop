
<script src="{!! asset('frontend/assets/js/jquery.min.js') !!}"></script>
<script src="{!! asset('frontend/assets/js/bootstrap.min.js') !!}"></script>
<script src="{!! asset('frontend/assets/js/sticky.min.js') !!}"></script>
<script src="{!! asset('frontend/assets/js/plugins.js') !!}"></script>
<script src="{!! asset('frontend/assets/js/main.js') !!}"></script>
<script src="{!! asset('frontend/assets/js/myFunction.js') !!}"></script>
<script src="{{asset('backend/dist/js/myFunction.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.10.2/validator.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        });
    </script>

<script>
    $(document).on('click', '.removeCart', function (e) {
        e.preventDefault();
        var removeCart = $(this).data('id');
        var name = $(this).data('name');
        var url =  "{!! route('web.remove.cart') !!}";
        $.ajax({
            url: '{!! route('web.remove.cart') !!}',
            type: 'post',
            data: {removeCart: removeCart , name: name},
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                location.reload();
                $('.cart_down').html(data.cart);
                $('.cart_down ').html(data.myCart)
            }
        });
    });

    $(document).on('change', '.qty', function (e) {
        e.preventDefault();
        var menu_details_id = $(this).data('id');
        var name = $(this).data('name');
        var qty = $(this).data('value');
        $.ajax({
            url: '{!! route('web.qty.cart') !!}',
            type: 'post',
            data: {menu_details_id: menu_details_id , name: name, qty : qty },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {
                $('.cart_down').html(data.cart);
                $('.cart_down ').html(data.myCart)
            }
        });
    });
</script>
@stack('js')

