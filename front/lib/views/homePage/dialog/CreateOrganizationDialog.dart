import 'package:flutter/material.dart';
import 'package:front/providers/CreateFolderProvider.dart';
import 'package:provider/provider.dart';

import 'CreateOrganizationForm.dart';

void CreateOrganizationDialog(BuildContext context) {
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
          child: CreateOrganizationForm(context),
        );
      });
}
