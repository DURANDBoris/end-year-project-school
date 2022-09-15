import 'package:flutter/cupertino.dart';

import '../models/core/User.dart';

class AuthenticationProvider extends ChangeNotifier {
  User _user = User(
    id: 0,
    token: "",
    firstname: "",
    lastName: "",
    email: "",
    listOrganization: [],
  );

  User get user => _user;

  set user(User value) {
    _user = value;
    notifyListeners();
  }

  String _token = "";

  String get token => _token;

  set token(String value) {
    _token = value;
    notifyListeners();
  }
}
