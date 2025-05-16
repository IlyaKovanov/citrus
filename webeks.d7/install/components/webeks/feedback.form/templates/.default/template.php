<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<section class="form__wrapper">
    <form id="feedbackForm" method="post">
        <div class="result"></div>
        <br>
        <label for="nameInput">Ваше имя*</label>
        <input id="nameInput" type="text" name="NAME" required>
        <label for="emailInput">Ваш телефон*</label>
        <input id="emailInput" type="text" name="PHONE" required>
        <label for="emailInput">Ваш Email*</label>
        <input id="emailInput" type="email" name="EMAIL" required>
        <label for="messageInput">Текст сообщения*</label>
        <textarea name="MESSAGE" id="messageInput" required></textarea>
        <input type="hidden" name="FORM_NAME" value="Форма на странице контакты">
        <i>* обязательно для заполнения</i>
        <button type="submit">Отправить</button>
        
    </form>
    
</section>

