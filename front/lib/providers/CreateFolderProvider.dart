import 'package:flutter/material.dart';
import 'package:front/models/core/Folder.dart';
import 'package:front/models/core/Rule.dart';

class CreateFolderProvider extends ChangeNotifier {
  int _indexIcon = 0;
  List<Rule> _listRule = [];

  Folder folder = Folder(id: 0, iconIndex: 0, name: "");

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
}
