import 'package:flutter/cupertino.dart';

class AppBarProvider extends ChangeNotifier {
  bool _isEditing = false;
  bool _isRuleOpen = false;
  bool _isDrawerOpen = true;

  bool get isEditing => _isEditing;

  set isEditing(bool value) {
    _isEditing = value;
    notifyListeners();
  }

  bool get isRuleOpen => _isRuleOpen;

  set isRuleOpen(bool value) {
    _isRuleOpen = value;
    notifyListeners();
  }

  bool get isDrawerOpen => _isDrawerOpen;

  set isDrawerOpen(bool value) {
    _isDrawerOpen = value;
    notifyListeners();
  }
}
