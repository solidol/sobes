/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function moveToArch(id) {
    var ask = window.confirm("Ви переносите документ до архіву, відмінити дію буде неможливо. Продовжити?");
    if (ask) {
        window.alert("Документ перенесено до архіву!");

        document.location.href = "/default.php/clerk/toarch/id:" + id;

    }
}

