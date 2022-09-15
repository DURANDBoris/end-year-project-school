import 'package:flutter/cupertino.dart';
import 'package:front/models/core/Organization.dart';
import 'package:front/models/core/User.dart';
import 'package:front/models/core/UserOrg.dart';
import 'package:front/models/service/OrganizationApi.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import 'package:front/providers/DrawerProvider.dart';
import 'package:front/providers/HomePageProvider.dart';
import 'package:front/providers/OrganizationDialogProvider.dart';
import 'package:front/views/homePage/homePage.dart';
import 'package:provider/provider.dart';

class OrganizationHelper {
  BuildContext context;

  OrganizationApi organizationApi = OrganizationApi();

  late AuthenticationProvider authentication;
  late DrawerProvider drawerProvider;
  late HomePageProvider homePageProvider;
  late OrganizationDialogProvider organizationDialogProvider;

  OrganizationHelper({required this.context}) {
    authentication =
        Provider.of<AuthenticationProvider>(context, listen: false);
    drawerProvider = Provider.of<DrawerProvider>(context, listen: false);
    homePageProvider = Provider.of<HomePageProvider>(context, listen: false);
    organizationDialogProvider =
        Provider.of<OrganizationDialogProvider>(context, listen: false);
  }

  Future<void> createOrganization({required String name}) async {
    Organization? organization = await organizationApi.createOrganization(
        name: name, authentication: authentication);
    if (organization == null) return;
    authentication.user.listOrganization.add(organization);
    drawerProvider.organization = organization;
    authentication.user = authentication.user;
    homePageProvider.document = null;
    homePageProvider.folder = null;
  }

  Future<void> deleteOrganization({required int idOrganization}) async {
    bool value = await organizationApi.deleteOrganization(
        idOrganization: idOrganization, authentication: authentication);
    if (!value) return;
    authentication.user.listOrganization
        .removeWhere((element) => element.id == idOrganization);
    drawerProvider.organization =
        authentication.user.listOrganization.isNotEmpty
            ? authentication.user.listOrganization.first
            : null;
    authentication.user = authentication.user;
    homePageProvider.document = null;
    homePageProvider.folder = null;
  }

  Future<void> getOrganizationUsers({required int idOrganization}) async {
    List<UserOrg> listUser = await organizationApi.getOrganizationUsers(
      idOrganization: idOrganization,
      authentication: authentication,
    );
    organizationDialogProvider.listUserOrg = listUser;
  }

  Future<bool> joinOrganization(
      {required String email, required int idOrganization}) async {
    int response = await organizationApi.joinOrganization(
      idOrganization: idOrganization,
      email: email,
      authentication: authentication,
    );
    if (response == 200) {
      getOrganizationUsers(idOrganization: idOrganization);
      return true;
    }
    return false;
  }

  Future<bool> leaveOrganization(
      {required int idUser, required int idOrganization}) async {
    int response = await organizationApi.leaveOrganization(
      idOrganization: idOrganization,
      idUser: idUser,
      authentication: authentication,
    );
    if (response == 200) {
      getOrganizationUsers(idOrganization: idOrganization);
      return true;
    }
    return false;
  }
}
