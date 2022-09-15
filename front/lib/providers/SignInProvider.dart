import 'package:flutter/material.dart';

class SignInProvider extends ChangeNotifier {
  String username = "";
  String password = "";

  bool _isObscure = false;

  bool get isObscure => _isObscure;

  set isObscure(bool value) {
    _isObscure = value;
    notifyListeners();
  }
}
