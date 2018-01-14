(function(){
    //標準エラーメッセージの変更
    $.extend($.validator.messages, {
        email: '*正しいメールアドレスの形式で入力して下さい',
        required: '*入力必須です',

        phone: "*正しい電話番号の形式で入力してください"
    });

    //追加ルールの定義
    var methods = {
        phone: function(value, element){
            return this.optional(element) || /^\d{11}$|^\d{3}-\d{4}-\d{4}$/.test(value);
        }
    };

    //メソッドの追加
    $.each(methods, function(key) {
        $.validator.addMethod(key, this);
    });

    //入力項目の検証ルール定義
    var rules = {
        userId: {required: true},
        userName: {required: true},
        passWord: {required: true},
        passWprd2:  {required: true},
        mailAddress: {required: true, email: true}
    };

    //入力項目ごとのエラーメッセージ定義
    var messages = {
        userId: {
            required: "*IDを入力してください"
        },
        userName: {
            required: "*名前を入力してください"
        },
        passWord: {
            required: "*パスワードを入力してください"
        },
        passWord2: {
            required: "*パスワードを入力してください"
        },
        mailAddress: {
            required: "*メアドを入力してください"
        }
    };

    $(function(){
        $('form').validate({
            rules: rules,
            messages: messages,

            //エラーメッセージ出力箇所調整
            errorPlacement: function(error, element){
                if (element.is(':radio')) {
                    error.appendTo(element.parent());
                }else {
                    error.insertAfter(element);
                }
            }
        });
    });

})();