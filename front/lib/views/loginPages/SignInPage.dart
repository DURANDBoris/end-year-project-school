import 'package:flutter/material.dart';
import 'package:front/models/helper/SignInHelper.dart';
import 'package:front/providers/SignInProvider.dart';
import 'package:front/views/formulates/SignInForm.dart';
import 'package:provider/provider.dart';
import 'SignInButtons.dart';

class SignInPage extends StatefulWidget {
  const SignInPage({Key? key}) : super(key: key);

  @override
  State<SignInPage> createState() => _SignInPage();
}

class _SignInPage extends State<SignInPage> {
  late SignInProvider signInProvider;
  late SignInHelper signInHelper;
  GlobalKey<FormState> signInKey = GlobalKey<FormState>();

  @override
  void initState() {
    super.initState();
    signInProvider = Provider.of<SignInProvider>(context, listen: false);
    signInHelper = SignInHelper(context: context);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        child: Center(
          child: SizedBox(
            width: 400,
            child: Column(
              children: [
                const SizedBox(height: 80),
                const FlutterLogo(
                  size: 200,
                ),
                const SizedBox(height: 40),
                const Text("File Flow"),
                const SizedBox(height: 40),
                signInForm(signInProvider, signInKey),
                const SizedBox(height: 40),
                signInButtons(context, signInKey, signInProvider, signInHelper)
              ],
            ),
          ),
        ),
      ),
    );
  }
}
