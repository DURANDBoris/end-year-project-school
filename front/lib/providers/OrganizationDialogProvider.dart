import 'package:flutter/cupertino.dart';
import 'package:front/models/core/Organization.dart';
import 'package:front/models/core/UserOrg.dart';

class OrganizationDialogProvider extends ChangeNotifier {
  late Organization? _currentOrganization;

  List<UserOrg> _listUserOrg = [];

  Organization? get currentOrganization => _currentOrganization;

  set currentOrganization(Organization? organization) {
    _currentOrganization = organization;
    notifyListeners();
  }

  set setCurrentOrganization(Organization? organization) {
    _currentOrganization = organization;
  }

  List<UserOrg> get listUserOrg => _listUserOrg;

  set listUserOrg(List<UserOrg> listUserOrg) {
    _listUserOrg = listUserOrg;
    notifyListeners();
  }
}
