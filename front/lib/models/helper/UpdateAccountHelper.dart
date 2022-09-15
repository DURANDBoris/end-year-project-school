import 'package:flutter/material.dart';
import 'package:front/models/core/AccountInput.dart';
import 'package:front/models/service/UpdateAccountApi.dart';
import 'package:front/providers/AccountProvider.dart';
import 'package:provider/provider.dart';

import '../../providers/AuthenticationProvider.dart';

class UpdateAccountHelper {
  BuildContext context;

  UpdateAccountApi updateAccountApi = UpdateAccountApi();

  late AccountProvider accountProvider;
  late AuthenticationProvider authentication;

  UpdateAccountHelper({required this.context}) {
    accountProvider = Provider.of<AccountProvider>(context, listen: false);
    authentication =
        Provider.of<AuthenticationProvider>(context, listen: false);
  }

  Future<int> updateAccount() async {
    int status = await updateAccountApi.updateAccount(
        input: AccountInput(
          password: accountProvider.password,
          userName: accountProvider.userName,
          firstName: accountProvider.firstName,
          lastName: accountProvider.lastName,
        ),
        authentication: authentication);
    print("status:$status");
    return status;
  }
}
