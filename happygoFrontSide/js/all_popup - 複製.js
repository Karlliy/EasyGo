//跳框_浮動客服 JS
function ShowService() {
    var box = document.getElementById('popup_Service');
    //取值，從高度 0px 加到 340px
    var totalH = 330,
        height = 0;
    box.style.height = "0px";
    //開啟視窗
    box.style.display = "block";
    //數字是總高除以數字 = 移動畫面的像素位置(數字越大越慢)
    var intervalID = setInterval(function() {
        if (totalH > height + (totalH / 100)) {
            height += (totalH / 100);
            box.style.height = height + "px";
        } else {
            box.style.height = totalH + "px";
            clearInterval(intervalID);
        }
    //速度設定(單位:毫秒，數字越大，捲動速度越慢)
    }, 1);
}

function all_popup() {
    //跳框_最新消息 (用多個class取代只能開啟單一id視窗問題)
    // let modal_jsNews = document.getElementsByClassName("jsNews");
    // for (var i = 0; i < modal_jsNews.length; i++) {
    //     modal_jsNews[i].addEventListener("click", function (e) {
    //         e.preventDefault();
    //         document.getElementById("popup_News").style.display = "block";
    //     });
    // }
    //跳框_註冊 (用多個class取代只能開啟單一id視窗問題)
    let modal_jsRegister = document.getElementsByClassName("jsRegister");
    for (var i = 0; i < modal_jsRegister.length; i++) {
        modal_jsRegister[i].addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_Register").style.display = "block";
            document.getElementById("popup_Login").style.display = "none";
        });
    }
    //跳框_登入 (用多個class取代只能開啟單一id視窗問題)
    let modal_jsLogin = document.getElementsByClassName("jsLogin");
    for (var i = 0; i < modal_jsLogin.length; i++) {
        modal_jsLogin[i].addEventListener("click", function (e) {
            // alert('123');
            e.preventDefault();
            document.getElementById("popup_Login").style.display = "block";
            document.getElementById("popup_Register").style.display = "none";
        });
    }
    //跳框_忘記密碼 (用多個class取代只能開啟單一id視窗問題)
    let modal_ForgotPW = document.getElementsByClassName("ForgotPW");
    for (var i = 0; i < modal_ForgotPW.length; i++) {
        modal_ForgotPW[i].addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_forgotpw").style.display = "block";
        });
    }
    //跳框_變更手機號碼 (用多個class取代只能開啟單一id視窗問題)
    let modal_ChangeMobile = document.getElementsByClassName("ChangeMobile");
    for (var i = 0; i < modal_ChangeMobile.length; i++) {
        modal_ChangeMobile[i].addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_ChangeMobile").style.display =
                "block";
        });
    }
    //跳框_已發送手機驗證碼 (用多個class取代只能開啟單一id視窗問題)
    let modal_jsMobileCode = document.getElementsByClassName("jsMobileCode");
    for (var i = 0; i < modal_jsMobileCode.length; i++) {
        modal_jsMobileCode[i].addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_MobileCode").style.display = "block";
        });
    }
    //跳框_收不到驗證碼 (用多個class取代只能開啟單一id視窗問題)
    let modal_jsNoCheck = document.getElementsByClassName("jsNoCheck");
    for (var i = 0; i < modal_jsNoCheck.length; i++) {
        modal_jsNoCheck[i].addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_NoCheck").style.display = "block";
        });
    }
    //跳框_所有"成功"訊息，都可以用這個 (用多個class取代只能開啟單一id視窗問題)
    // let modal_jsSentSuccess = document.getElementsByClassName("jsSentSuccess");
    // for (var i = 0; i < modal_jsSentSuccess.length; i++) {
    //     modal_jsSentSuccess[i].addEventListener("click", function (e) {
    //         e.preventDefault();
    //         document.getElementById("popup_SentSuccess").style.display = "block";
    //     });
    // }
    //跳框_資訊有誤！所有"失敗"訊息，都可以用這個 (用多個class取代只能開啟單一id視窗問題)
    // let modal_jsSentFail = document.getElementsByClassName("jsSentFail");
    // for (var i = 0; i < modal_jsSentFail.length; i++) {
    //     modal_jsSentFail[i].addEventListener("click", function (e) {
    //         e.preventDefault();
    //         document.getElementById("popup_SentFail").style.display = "block";
    //     });
    // }
    //提交驗證 (用多個class取代只能開啟單一id視窗問題)
    let modal_jsVerifyConfirm = document.getElementsByClassName("jsVerifyConfirm");
    for (var i = 0; i < modal_jsVerifyConfirm.length; i++) {
        modal_jsVerifyConfirm[i].addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_VerifyConfirm").style.display = "block";
        });
    }
    //跳框_回覆評價 (用多個class取代只能開啟單一id視窗問題)
    // let modal_ReplyComment = document.getElementsByClassName("ReplyComment");
    // for (var i = 0; i < modal_ReplyComment.length; i++) {
    //     modal_ReplyComment[i].addEventListener("click", function (e) {
    //         e.preventDefault();
    //         document.getElementById("popup_ReplyComment").style.display =
    //             "block";
    //     });
    // }
    //跳框_給予評價 (用多個class取代只能開啟單一id視窗問題)
    // let modal_js_BuyComment = document.getElementsByClassName("js_BuyComment");
    // for (var i = 0; i < modal_js_BuyComment.length; i++) {
    //     modal_js_BuyComment[i].addEventListener("click", function (e) {
    //         e.preventDefault();
    //         document.getElementById("popup_BuyComment").style.display = "block";
    //     });
    // }
    //跳框_查看(申訴內容) (用多個class取代只能開啟單一id視窗問題)
    let modal_jsCheckComplain = document.getElementsByClassName("jsCheckComplain");
    for (var i = 0; i < modal_jsCheckComplain.length; i++) {
        modal_jsCheckComplain[i].addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_CheckComplain").style.display = "block";
        });
    }
    //開啟內容(付款方式)
    let modal_Payment = document.getElementById("Payment");
    if (modal_Payment != null) {
        modal_Payment.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_Payment").style.display = "block";
        });
    }
    //開啟內容(錢包餘額繳款)
    let modal_WalletList = document.getElementById("WalletList");
    if (modal_WalletList != null) {
        modal_WalletList.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_WalletList").style.display = "block";
        });
    }
    //開啟內容(線上繳款)
    let modal_OnlinePayList = document.getElementById("OnlinePayList");
    if (modal_OnlinePayList != null) {
        modal_OnlinePayList.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_OnlinePayList").style.display = "block";
        });
    }
    //開啟內容(ATM繳款)
    let modal_AtmPayList = document.getElementById("AtmPayList");
    if (modal_AtmPayList != null) {
        modal_AtmPayList.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_AtmPayList").style.display = "block";
        });
    }
    //開啟內容(超商繳款)
    let modal_CvsPayList = document.getElementById("CvsPayList");
    if (modal_CvsPayList != null) {
        modal_CvsPayList.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_CvsPayList").style.display = "block";
        });
    }
    // let modal_CvsPayList2 = document.getElementById("CvsPayList2");
    // if (modal_CvsPayList2 != null) {
    //     modal_CvsPayList2.addEventListener("click", function (e) {
    //         e.preventDefault();
    //         document.getElementById("popup_CvsPayList").style.display = "block";
    //     });
    // }
    // let modal_CvsPayList3 = document.getElementById("CvsPayList3");
    // if (modal_CvsPayList3 != null) {
    //     modal_CvsPayList3.addEventListener("click", function (e) {
    //         e.preventDefault();
    //         document.getElementById("popup_CvsPayList").style.display = "block";
    //     });
    // }
    // let modal_CvsPayList4 = document.getElementById("CvsPayList4");
    // if (modal_CvsPayList4 != null) {
    //     modal_CvsPayList4.addEventListener("click", function (e) {
    //         e.preventDefault();
    //         document.getElementById("popup_CvsPayList").style.display = "block";
    //     });
    // }
    //跳框(錢包餘額繳款)
    let modal_Wallet = document.getElementById("Wallet");
    if (modal_Wallet != null) {
        modal_Wallet.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_Wallet").style.display = "block";
        });
    }
    //跳框(安達手機驗證頁面)
    let modal_API17adpay = document.getElementById("CvsPay");
    if (modal_API17adpay != null) {
        modal_API17adpay.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_API17adpay").style.display = "block";
        });
    }
    //跳框(線上繳款)
    let modal_OnlinePay = document.getElementById("OnlinePay");
    if (modal_OnlinePay != null) {
        modal_OnlinePay.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_OnlinePay").style.display = "block";
        });
    }
    //跳框(ATM-銀行虛擬帳號)
    // let modal_AtmPay = document.getElementById("AtmPay");
    // if (modal_AtmPay != null) {
    //     modal_AtmPay.addEventListener("click", function (e) {
    //         e.preventDefault();
    //         document.getElementById("popup_AtmPay").style.display = "block";
    //     });
    // }
    //跳框(超商-繳費代碼)
    // let modal_CvsPay = document.getElementById("CvsPay");
    // if (modal_CvsPay != null) {
    //     modal_CvsPay.addEventListener("click", function (e) {
    //         e.preventDefault();
    //         document.getElementById("popup_CvsPay").style.display = "block";
    //     });
    // }
    //跳框_(複製繳費代碼或銀行帳號) (用多個class取代只能開啟單一id視窗問題)
    let modal_Copy = document.getElementsByClassName("Copy");
    for (var i = 0; i < modal_Copy.length; i++) {
        modal_Copy[i].addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_Copy").style.display = "block";
        });
    }
    //跳框_(取消訂單) (用多個class取代只能開啟單一id視窗問題)
    // let modal_jsCanclePay = document.getElementsByClassName("jsCanclePay");
    // for (var i = 0; i < modal_jsCanclePay.length; i++) {
    //     modal_jsCanclePay[i].addEventListener("click", function (e) {
    //         e.preventDefault();
    //         document.getElementById("popup_CanclePay").style.display = "block";
    //     });
    // }
    //跳框_(賣家取消訂單) (用多個class取代只能開啟單一id視窗問題)
    // let modal_jsSellCanclePay = document.getElementsByClassName("jsSellCanclePay");
    // for (var i = 0; i < modal_jsSellCanclePay.length; i++) {
    //     modal_jsSellCanclePay[i].addEventListener("click", function (e) {
    //         e.preventDefault();
    //         document.getElementById("popup_SellCanclePay").style.display = "block";
    //     });
    // }
    //跳框_(安全驗證)
    let modal_Verify = document.getElementById("Verify");
    if (modal_Verify != null) {
        modal_Verify.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_Verify").style.display = "block";
        });
    }
    //跳框_(交易訊息提示)
    let modal_Message = document.getElementById("Message");
    if (modal_Message != null) {
        modal_Message.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_Message").style.display = "block";
        });
    }
    //跳框_(提款)
    let modal_Withdraw = document.getElementById("Withdraw");
    if (modal_Withdraw != null) {
        modal_Withdraw.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_Withdraw").style.display = "block";
        });
    }
    //跳框_刪除
    let modal_jsDeleteInfo = document.getElementsByClassName("jsDeleteInfo");
    for (var i = 0; i < modal_jsDeleteInfo.length; i++) {
        modal_jsDeleteInfo[i].addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_DeleteInfo").style.display = "block";
        });
    }
    //跳框_新增銀行
    let modal_AddBank = document.getElementById("AddBank");
    if (modal_AddBank != null) {
        modal_AddBank.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_AddBank").style.display = "block";
        });
    }
    //跳框_完成新增銀行
    let modal_AddBankOk = document.getElementById("AddBankOk");
    if (modal_AddBankOk != null) {
        modal_AddBankOk.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_AddBankOk").style.display = "block";
        });
    }
    //銀行明細
    let modal_AddBankInfo = document.getElementById("AddBankInfo");
    if (modal_AddBankInfo != null) {
        modal_AddBankInfo.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_AddBankInfo").style.display = "block";
        });
    }
    //訂單收入 (用多個class取代只能開啟單一id視窗問題)
    let modal_jsWalletIncome = document.getElementsByClassName("jsWalletIncome");
    for (var i = 0; i < modal_jsWalletIncome.length; i++) {
        modal_jsWalletIncome[i].addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_WalletIncome").style.display = "block";
        });
    }
    //錢包提款 (用多個class取代只能開啟單一id視窗問題)
    let modal_jsWalletWithdraw = document.getElementsByClassName("jsWalletWithdraw");
    for (var i = 0; i < modal_jsWalletWithdraw.length; i++) {
        modal_jsWalletWithdraw[i].addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_WalletWithdraw").style.display = "block";
        });
    }
    //訂單退款 (用多個class取代只能開啟單一id視窗問題)
    let modal_jsWalletRefund = document.getElementsByClassName("jsWalletRefund");
    for (var i = 0; i < modal_jsWalletRefund.length; i++) {
        modal_jsWalletRefund[i].addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_WalletRefund").style.display = "block";
        });
    }
    //錢包支付 (用多個class取代只能開啟單一id視窗問題)
    let modal_jsWalletPay = document.getElementsByClassName("jsWalletPay");
    for (var i = 0; i < modal_jsWalletPay.length; i++) {
        modal_jsWalletPay[i].addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById("popup_WalletPay").style.display = "block";
        });
    }
}
