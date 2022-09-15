import 'package:flutter/material.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import 'package:front/views/homePage/homePage.dart';
import 'package:front/views/loginPages/SignInPage.dart';
import 'package:provider/provider.dart';

class Splashscreen extends StatefulWidget {
  const Splashscreen({Key? key}) : super(key: key);

  @override
  State<Splashscreen> createState() => _SplashscreenState();
}

class _SplashscreenState extends State<Splashscreen> {
  @override
  Widget build(BuildContext context) {
    return Selector<AuthenticationProvider, String>(
        selector: (_, provider) => provider.token,
        shouldRebuild: (previous, next) => true,
        builder: (_, token, __) {
          return token.isEmpty ? SignInPage() : HomePage();
        });
  }
}
