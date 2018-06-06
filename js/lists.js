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

function docDelete(id) {
    var ask = window.confirm("Ви видаляєте документ, відмінити дію буде неможливо. Продовжити?");
    if (ask) {
        window.alert("Документ видалено!");

        $.post("/default.php/ajax/remove/id:" + id, function (data) {
            setTimeout(function () {
                window.table.ajax.reload();
            }, 200);
        });


    }
}

