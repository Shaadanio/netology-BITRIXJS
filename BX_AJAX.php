<?
//подключаем пролог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

//устанавливаем заголовок страницы
$APPLICATION->SetTitle("AJAX");

//Инициализируем ядро AJAX
CJSCore::Init(array('ajax'));

//Инициализируем переменную $sidAjax, содержащую идентификатор формы 'testAjax'.
$sidAjax = 'testAjax';

//Если запрос получен из формы 'testAjax', отправляем данные с помощью функции CUtil::PhpToJSObject()
if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){
   $GLOBALS['APPLICATION']->RestartBuffer();
   echo CUtil::PhpToJSObject(array(
            'RESULT' => 'HELLO',
            'ERROR' => ''
   ));
   die();
}

?>

<!-- html -->
<div class="group">
   <div id="block"></div >
   <div id="process">wait ... </div >
</div>
<!-- End html -->

<!-- Вставка JS -->
<script>

    //Включение отладочной информации в ядре Битрикс
    window.BXDEBUG = true;

    //Создаем функцию DEMOLoad(): скрываем div с id="block", показываем div с id="process" и загружаем данные с помощью ajax.loadJSON()
    function DEMOLoad(){
        BX.hide(BX("block"));
        BX.show(BX("process"));
        BX.ajax.loadJSON(
            '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>',
            DEMOResponse
        );
    }

    //Создаем функцию DEMOResponse() для обработки ответа сервера: выводим результат в div с id="block", скрываем div с id="process" и вызываем событие DEMOUpdate на элементе соответствующему div с id="block".
    function DEMOResponse (data){
        BX.debug('AJAX-DEMOResponse ', data);
        BX("block").innerHTML = data.RESULT;
        BX.show(BX("block"));
        BX.hide(BX("process"));

        BX.onCustomEvent(
            BX(BX("block")),
            'DEMOUpdate'
        );
    }

    //Ждем готовности страницы, скрываем div с id="block" и div с id="process", привязываем обработчик события клика на элементах с классом "css_ajax" и вызываем функцию DEMOLoad() при клике на этих элементах.
    BX.ready(function(){
        /*
        BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){
            window.location.href = window.location.href;
        });
        */
        BX.hide(BX("block"));
        BX.hide(BX("process"));
        
            BX.bindDelegate(
            document.body, 'click', {className: 'css_ajax' },
            function(e){
                if(!e)
                    e = window.event;
                
                DEMOLoad();
                return BX.PreventDefault(e);
            }
        );
    
    });

</script>
<!-- End html -->

<!-- html  -->
<div class="css_ajax">click Me</div>
<!-- End Вставка JS -->
<?
//подключаем эпилог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>