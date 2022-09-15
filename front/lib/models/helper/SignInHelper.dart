import 'package:flutter/material.dart';
import 'package:front/models/core/User.dart';
import 'package:front/models/service/SignInApi.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import 'package:front/providers/DrawerProvider.dart';
import 'package:front/providers/SignInProvider.dart';
import 'package:provider/provider.dart';

class SignInHelper {
  BuildContext context;

  SignInApi signInApi = SignInApi();

  late SignInProvider signInProvider;
  late AuthenticationProvider authentication;
  late DrawerProvider drawerProvider;

  SignInHelper({required this.context}) {
    signInProvider = Provider.of<SignInProvider>(context, listen: false);
    drawerProvider = Provider.of<DrawerProvider>(context, listen: false);
    authentication =
        Provider.of<AuthenticationProvider>(context, listen: false);
  }

  Future<void> login() async {
    User? user = await signInApi.login(
      username: signInProvider.username,
      password: signInProvider.password,
    );
    if (user == null) return;
    authentication.user = user;
    authentication.token = user.token;
    if (user.listOrganization.isEmpty) return;
    drawerProvider.organization = user.listOrganization.first;
    return;
  }
}
