import 'package:flutter/material.dart';
import 'package:front/providers/CreateFolderProvider.dart';
import 'package:front/views/homePage/dialog/JoinOrganizationForm.dart';
import 'package:provider/provider.dart';

void JoinOrganizationDialog(
    {required BuildContext context, required int idOrganization}) {
  CreateFolderProvider createFolderProvider =
      Provider.of<CreateFolderProvider>(context, listen: false);

  createFolderProvider.listRule = [];
  createFolderProvider.indexIcon = 0;

  showDialog(
      context: context,
      builder: (context) {
        return Dialog(
          shape:
              RoundedRectangleBorder(borderRadius: BorderRadius.circular(30.0)),
          child: JoinOrganizationForm(
              context: context, idOrganization: idOrganization),
        );
      });
}
