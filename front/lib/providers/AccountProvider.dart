import 'package:flutter/foundation.dart';

class AccountProvider extends ChangeNotifier {
  String lastName = "";
  String firstName = "";
  String userName = "";
  String password = "";
  String passField1 = "";
  String passField2 = "";

  bool _isObscure = true;
  bool _isEqual = false;
  bool _isCap = false;
  bool _isMin = false;
  bool _isSpecial = false;
  bool _isNum = false;
  bool _isLong = false;

  int _hard = 0;

  bool get isObscure => _isObscure;

  set isObscure(bool value) {
    _isObscure = value;
    notifyListeners();
  }

  bool get isEqual => _isEqual;

  set isEqual(bool value) {
    _isEqual = value;
    notifyListeners();
  }

  bool get isCap => _isCap;

  set isCap(bool value) {
    _isCap = value;
    notifyListeners();
  }

  bool get isMin => _isMin;

  set isMin(bool value) {
    _isMin = value;
    notifyListeners();
  }

  bool get isSpecial => _isSpecial;

  set isSpecial(bool value) {
    _isSpecial = value;
    notifyListeners();
  }

  bool get isNum => _isNum;

  set isNum(bool value) {
    _isNum = value;
    notifyListeners();
  }

  bool get isLong => _isLong;

  set isLong(bool value) {
    _isLong = value;
    notifyListeners();
  }

  int get hard => _hard;

  set hard(int value) {
    _hard = value;
    notifyListeners();
  }
}
