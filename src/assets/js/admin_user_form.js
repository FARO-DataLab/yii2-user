$(document).ready(function () {

    /*
     * funciones para dar soporte a la pantalla de formulario de usuario
     * sus principales responsabilidades son:
     *  - mostrar cuando corresponda la seccion de seleccion de categorias
     *  - mostrar cuando corresponda la razon de expulsion de usuario
     * 
     **/

    let rolUsuario = null;
    let $seccionSelectorCategorias = $(".usuario-selector-categorias");
    let $inputRol = $("#user-role_id");

    function init() {
        cambioRolUsuario();
    }

    function toggleSelectorCampanas() {
        if (rolUsuario === "2") {
            $seccionSelectorCategorias.removeClass("d-none");
        } else {
            $seccionSelectorCategorias.addClass("d-none");
        }
    }

    function cambioRolUsuario() {
        rolUsuario = $('#user-role_id').val();
        toggleSelectorCampanas();
    }

    init();
    $inputRol.on("change", function () {
        cambioRolUsuario();
    });

});