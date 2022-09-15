import 'package:flutter/material.dart';
import 'package:front/models/core/Rule.dart';

class UpdateFolderProvider extends ChangeNotifier {
  int _indexIcon = 0;
  List<Rule> _listRule = [];

  int get indexIcon => _indexIcon;

  set indexIcon(int value) {
    _indexIcon = value;
    notifyListeners();
  }

  List<Rule> get listRule => _listRule;

  set listRule(List<Rule> value) {
    _listRule = value;
    notifyListeners();
  }

  set setListRule(List<Rule> value) {
    _listRule = value;
  }
}
