import 'package:flutter/cupertino.dart';
import 'package:front/models/core/Index.dart';
import 'package:front/models/core/Rule.dart';
import '/models/core/Folder.dart';

import '../models/core/Document.dart';

class HomePageProvider extends ChangeNotifier {
  List<Document> _listDocument = [];

  List<Document> get listDocument => _listDocument;

  set listDocument(List<Document> listDocument) {
    _listDocument = listDocument;
    notifyListeners();
  }

  List<Document> _listDownload = [];

  List<Document> get listDownload => _listDownload;

  set listDownload(List<Document> listDownload) {
    _listDownload = listDownload;
    notifyListeners();
  }

  List<Rule> _listRule = [];

  List<Rule> get listRule => _listRule;

  set listRule(List<Rule> listRule) {
    _listRule = listRule;
    notifyListeners();
  }

  List<Index> _listIndex = [];

  List<Index> get listIndex => _listIndex;

  set listIndex(List<Index> listIndex) {
    _listIndex = listIndex;
    notifyListeners();
  }

  List<bool> _listBool = [];

  List<bool> get listBool => _listBool;

  set listBool(List<bool> listBool) {
    _listBool = listBool;
    notifyListeners();
  }

  set firstListBool(List<bool> listBool) {
    _listBool = listBool;
  }

  Folder? _folder;

  Folder? get folder => _folder;

  set folder(Folder? value) {
    _folder = value;
    notifyListeners();
  }

  Document? _document;

  Document? get document => _document;

  set document(Document? value) {
    _document = value;
    notifyListeners();
  }
}
