import 'dart:io';

import 'package:flutter/material.dart';
import 'package:front/models/helper/UpdateAccountHelper.dart';
import 'package:front/providers/AccountProvider.dart';
import 'package:front/views/formulates/EmailAccountForm.dart';
import 'package:front/views/formulates/IdentityAccountForm.dart';
import 'package:front/views/formulates/PasswordAccountForm.dart';
import 'package:provider/provider.dart';

Widget UpdateAccountForm(BuildContext context) {
  AccountProvider accountProvider =
      Provider.of<AccountProvider>(context, listen: false);
  UpdateAccountHelper updateAccountHelper =
      UpdateAccountHelper(context: context);
  GlobalKey<FormState> updateAccountKey = GlobalKey<FormState>();

  return Form(
    key: updateAccountKey,
    child: Container(
      width: 400,
      margin: EdgeInsets.fromLTRB(20.0, 30.0, 20.0, 10.0),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          SizedBox(height: 20),
          Text("Update Your Information"),
          SizedBox(height: 20),
          identityAccountForm(accountProvider),
          emailAccountForm(accountProvider),
          passwordAccountForm(accountProvider),
          SizedBox(height: 40),
          ElevatedButton(
            onPressed: () async {
              if (updateAccountKey.currentState!.validate()) {
                if (await updateAccountHelper.updateAccount() ==
                    HttpStatus.ok) {
                  Navigator.of(context).pop();
                }
              }
            },
            child: Container(
              width: 100,
              height: 40,
              alignment: Alignment.center,
              child: Text('update'),
            ),
          ),
          SizedBox(height: 20)
        ],
      ),
    ),
  );
}
