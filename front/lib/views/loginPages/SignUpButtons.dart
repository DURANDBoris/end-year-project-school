import 'dart:io';

import 'package:flutter/material.dart';
import 'package:front/models/helper/SignUpHelper.dart';
import 'package:front/providers/AccountProvider.dart';

Widget signUpButtons(BuildContext context, GlobalKey<FormState> signUpKey,
    AccountProvider accountProvider, SignUpHelper signUpHelper) {
  return Row(
    children: [
      ElevatedButton(
        onPressed: () {
          Navigator.of(context).pop();
        },
        child: Container(
          width: 100,
          height: 40,
          alignment: Alignment.center,
          child: Icon(Icons.arrow_back),
        ),
      ),
      Spacer(),
      ElevatedButton(
        onPressed: () async {
          if (signUpKey.currentState!.validate()) {
            if (await signUpHelper.signUp() == HttpStatus.ok) {
              Navigator.of(context).pop();
            }
          }
        },
        child: Container(
          width: 100,
          height: 40,
          alignment: Alignment.center,
          child: Text('sign up'),
        ),
      ),
    ],
  );
}
