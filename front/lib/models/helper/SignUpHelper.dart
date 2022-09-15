import 'package:flutter/material.dart';
import 'package:front/models/core/AccountInput.dart';
import 'package:front/models/service/SignUpApi.dart';
import 'package:front/providers/AccountProvider.dart';
import 'package:provider/provider.dart';

class SignUpHelper {
  BuildContext context;

  SignUpApi signUpApi = SignUpApi();

  late AccountProvider accountProvider;

  SignUpHelper({required this.context}) {
    accountProvider = Provider.of<AccountProvider>(context, listen: false);
  }

  Future<int> signUp() async {
    int status = await signUpApi.resultSignUp(
        input: AccountInput(
            password: accountProvider.password,
            userName: accountProvider.userName,
            firstName: accountProvider.firstName,
            lastName: accountProvider.lastName));
    return status;
  }
}
