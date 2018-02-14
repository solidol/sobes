/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



var contentold = {};   //объявляем переменную для хранения неизменного текста
function savedata(elementidsave, contentsave) {   //функция для сохранения отредактированного текста с помощью ajax
        //записываем присланные данные от сервера в элемент, который редактировался
                $('<div id="status" style="color:green;">Збереження</div>')   //выводим сообщение об успешном ответе сервера
                        .insertAfter('#' + elementidsave)
                        .addClass("success")
                        .fadeIn('fast')
                        .delay(1000)
                        .fadeOut('slow', function () {
                            this.remove();
                        }); //уничтожаем элемент
    $.ajax({
        url: '/default.php/ajax/autosave/push', //url который обрабатывает и сохраняет наш текст
        type: 'POST',
        data: {
            content: contentsave, //наш пост запрос
            id: elementidsave
        },
        success: function (data) {      //получили ответ от сервера - обрабатываем

            if (data == contentsave)   //сервер прислал нам отредактированный текст, значит всё ok
            {
                $('#' + elementidsave).html(data);      //записываем присланные данные от сервера в элемент, который редактировался
                $('<div id="status" style="color:green;">Данные успешно сохранены:' + data + '</div>')   //выводим сообщение об успешном ответе сервера
                        .insertAfter('#' + elementidsave)
                        .addClass("success")
                        .fadeIn('fast')
                        .delay(2000)
                        .fadeOut('slow', function () {
                            this.remove();
                        }); //уничтожаем элемент

            } else
            {
                $('<div id="status" style="color:red;>Запрос завершился ошибкой:' + data + '</div>') // выводим данные про ошибку
                        .insertAfter('#' + elementidsave)
                        .addClass("error")
                        .fadeIn('fast')
                        .delay(3000)
                        .fadeOut('slow', function () {
                            this.remove();
                        });  //уничтожаем элемент
            }
        }
    });
}
$(document).ready(function () {
    $('[contenteditable="true"]')                 //редактируемый элемент
            .mousedown(function (e)                       //обрабатываем событие нажатие мышки 
            {
                e.stopPropagation();
                elementid = this.id;
                contentold[elementid] = $(this).text();        //текст до редактирования
                $(this).bind('keydown', function (e) {         //обработчик нажатия Escape
                    if (e.keyCode == 27) {
                        e.preventDefault();
                        $(this).html(contentold[elementid]);	//возвращаем текст до редактирования	
                    }
                });
                $("#save").show();                              //показываем кнопку "сохранить"
            })
            .blur(function (event)                   //обрабатываем событие потери фокуса
            {
                var elementidsave = this.id;                       //id элемента потерявшего фокус         
                var contentsave = $(this).text();           //текст для сохранения
                event.stopImmediatePropagation();
                if (elementid === elementidsave)    // если id не совпадает с id элемента, потерявшего фокус,
                {
                    $("#save").hide();
                }      // значит фокус  в редактируемом элементе, кнопку не прячем
                if (contentsave != contentold[elementidsave])  //если текст изменился           
                {
                    savedata(elementidsave, contentsave);   //отправляем на сервер
                }
            });
});
 