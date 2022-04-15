// function all_popup() {
$(function() { 

    //News
        //查詢
        let modal_item_search = document.getElementById('item_search');
        modal_item_search.addEventListener('click', function(e){
            e.preventDefault();
            document.getElementById('popup_search').style.display='block';
        });
        //新增
        let modal_item_add = document.getElementById('item_add');
        modal_item_add.addEventListener('click', function(e){
            e.preventDefault();
            document.getElementById('popup_itemadd').style.display='block';
        });
        //確認新增
        let modal_add_confirm = document.getElementById('add_confirm');
        modal_add_confirm.addEventListener('click', function(e){
            e.preventDefault();
            document.getElementById('popup_confirmAdd').style.display='block';
        });
        //編輯
        //let modal_item_edit = document.getElementById('item_edit');
        //modal_item_edit.addEventListener('click', function(e){
        $('input[id="item_edit"]').on('click', function (e) {
            e.preventDefault();
            document.getElementById('popup_itemedit').style.display='block';
        });
        //確認修改
        let modal_edit_confirm = document.getElementById('edit_confirm');
        modal_edit_confirm.addEventListener('click', function(e){
            e.preventDefault();
            document.getElementById('popup_confirmEdit').style.display='block';
        });
        //刪除
        $('input[id="item_del"]').on('click', function (e) { 
            e.preventDefault();
            document.getElementById('popup_del').style.display='block';
        });



});