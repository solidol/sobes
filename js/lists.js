/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var table;

function moveToArch(id) {
    var ask = window.confirm("Ви переносите документ до архіву, відмінити дію буде неможливо. Продовжити?");
    if (ask) {
        window.alert("Документ перенесено до архіву!");

        $.post("/default.php/ajax/toarch/id:" + id, function (data) {
            setTimeout(function () {
                window.table.ajax.reload();
            }, 200);
        });


    }
}

