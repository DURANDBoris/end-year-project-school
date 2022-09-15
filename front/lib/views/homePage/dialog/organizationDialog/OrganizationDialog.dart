import 'package:flutter/material.dart';
import 'package:front/models/core/Organization.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import 'package:front/providers/ListFoldersProvider.dart';
import 'package:front/providers/OrganizationDialogProvider.dart';
import 'package:front/views/homePage/dialog/organizationDialog/widgets/ListOrganizationWidget.dart';
import 'package:front/views/homePage/dialog/organizationDialog/widgets/OrganizationWidget.dart';
import 'package:provider/provider.dart';

void OrganizationDialog({required BuildContext context}) {
  showDialog(
      context: context,
      builder: (context) {
        return Dialog(
          shape:
              RoundedRectangleBorder(borderRadius: BorderRadius.circular(30.0)),
          child: OrganizationPage(context: context),
        );
      });
}

Widget OrganizationPage({required BuildContext context}) {
  AuthenticationProvider authentication =
      Provider.of<AuthenticationProvider>(context, listen: false);

  OrganizationDialogProvider organizationDialogProvider =
      Provider.of<OrganizationDialogProvider>(context, listen: false);

  List<Organization> listOrganization = authentication.user.listOrganization;

  organizationDialogProvider.setCurrentOrganization =
      listOrganization.isEmpty ? null : listOrganization.first;
  return SizedBox(
    width: 1000,
    height: 800,
    child: Row(
      children: [
        Expanded(
          flex: 2,
          child: listOrganizationWidget(
            organizationDialogProvider: organizationDialogProvider,
            context: context,
          ),
        ),
        VerticalDivider(),
        Expanded(
          flex: 8,
          child: Selector<OrganizationDialogProvider, Organization?>(
              selector: (context, provider) => provider.currentOrganization,
              builder: (context, organization, child) {
                if (organization == null) return Container();
                return organizationWidget(
                  organization: organization,
                  context: context,
                );
              }),
        ),
      ],
    ),
  );
}
