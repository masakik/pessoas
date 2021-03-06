@extends('laravel-usp-theme::master')

@section('title', 'Dashboard')

@section('content_header')
<h1></h1>
@stop

@section('content')
@include('alerts')

<div class="row">
    <div class="col-lg-5">
        <form id="busca" method="POST" action="codpes">
            @csrf
            <div class="form-group">
                <label for="usr">Nome</label>
                <input type="text" class="form-control" name="nompes" id="nompes" autocomplete="off" required>
                <ul name="search" id="search"></ul>
            </div>
            <input type="hidden" value="" name="codpes" id="codpes">
        </form>
    </div>
</div>

@stop

@section('javascripts_bottom')
<script>
$(document).ready(function() {

    $(window).keydown(function(event) {
        if ((event.keyCode == 13)) {
            event.preventDefault();
            return false;
        }
    });

    $('#nompes').keyup(function() {
        var term = $.trim($(this).val());
        var ul_search = $('#search');
        ul_search.empty().removeClass("dropdown-menu").css("display:none;");
        setTimeout(function() {
            if (term.length >= 5 && $.trim($('#nompes').val()) == term) {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('autocomplete.search') }}",
                    data: {
                        term: term,
                        _token: _token
                    },
                    datatype: "json",
                    beforeSend: function() {
                        ul_search.empty().append("<li><a href='#'>Buscando... Aguarde!</a></li>")
                            .addClass("dropdown-menu").attr("style","display:block; position:relative");
                    },
                    success: function(data) {
                        ul_search.empty();
                        if (data.length > 0) {
                            $.each(data, function(key, value) {
                                ul_search.append('<li value=' + value
                                    .codpes + '><a href="#">(' + value
                                    .codpes + ') ' + value.nompes +
                                    '</a></li>');
                            });
                        } else {
                            ul_search.append("<li><a href='#'>Nenhum registro encontrado!</a></li>");
                        }

                    },
                    complete: function() {
                        ul_search.addClass("dropdown-menu").attr("style","display:block; position:relative").fadeIn();
                    }
                });
            }
        }, 1000);
    });

    $(document).on('click', '#search > li', function() {
        if ($(this).val() > 0) {
            $('#nompes').val($(this).text());
            $('#codpes').val($(this).val());
            $('#search').fadeOut();
            $('#busca').submit();
        }
    });

});
</script>

@stop