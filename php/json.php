<?php

$json = '{
    "Payment": {
        "Amount": "2597",
        "Articles": null,
        "BillingAddress": {
            "City": "fsd",
            "Country": "ES",
            "HouseExtension": null,
            "HouseNumber": null,
            "ID": 1650,
            "State": "Albacete",
            "Street": "aa ss dfd dfs",
            "StreetNumber": null,
            "ZipCode": "34234234"
        },
        "Capture": null,
        "ClientIP": null,
        "Created": "20170303034533",
        "Currency": "EUR",
        "CustomParameters": null,
        "Customer": {
            "Company": null,
            "DateOfBirth": null,
            "Email": "zfxie@i9i8.com",
            "FirstName": "allen",
            "Gender": null,
            "ID": 3353,
            "LastName": "xie",
            "MerchantCustomerID": "2295478",
            "Phone": "3243243434",
            "SocialSecurityNumber": null
        },
        "Description": "Trustly",
        "Details": null,
        "ExcludeMethodIDs": null,
        "ID": 2819407,
        "IncludeMethodIDs": null,
        "MerchantTransactionID": "2555357656_170302194529",
        "MethodID": 29,
        "MethodOptionID": null,
        "MethodTransactionID": null,
        "NotificationDateTime": null,
        "OriginatorTransactionID": null,
        "PreapprovalID": null,
        "PrioritizeMethodIDs": null,
        "RedirectURL": "https://apitest.smart2pay/Home?PaymentToken=31576F821C725E837FCA758192FD1D34.2819407",
        "ReferenceDetails": null,
        "ReturnURL": "https://dev.jjshouse.com/checkout_success.php?action=orderconfirm&order_sn=2555357656",
        "ShippingAddress": {
            "City": "fsd",
            "Country": "ES",
            "HouseExtension": null,
            "HouseNumber": null,
            "ID": 1650,
            "State": "Albacete",
            "Street": "aa ss dfd dfs",
            "StreetNumber": null,
            "ZipCode": "34234234"
        },
        "SiteID": 33332,
        "SkinID": null,
        "Status": {
            "ID": 2,
            "Info": "Success",
            "Reasons": null
        },
        "TokenLifetime": 15
    }
}';

$res = json_decode($json, true);

var_dump($res);