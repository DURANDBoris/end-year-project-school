import 'package:flutter/material.dart';
import 'package:front/models/helper/SignInHelper.dart';
import 'package:front/providers/SignInProvider.dart';

import 'SignUpPage.dart';

Widget signInButtons(BuildContext context, GlobalKey<FormState> signInKey,
    SignInProvider signInProvider, SignInHelper signInHelper) {
  return Row(
    mainAxisSize: MainAxisSize.min,
    children: [
      const SizedBox(width: 35),
      ElevatedButton(
        onPressed: () {
          if (signInKey.currentState!.validate()) {
            signInHelper.login();
          }
        },
        child: Container(
          width: 100,
          height: 40,
          alignment: Alignment.center,
          child: Text('sign in'),
        ),
      ),
      SizedBox(width: 50),
      ElevatedButton(
        onPressed: () => Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => SignUpPage(),
          ),
        ),
        child: Container(
          width: 100,
          height: 40,
          alignment: Alignment.center,
          child: Text('sign up'),
        ),
      )
    ],
  );
}
