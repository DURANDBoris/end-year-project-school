import 'package:flutter/material.dart';
import 'package:front/models/core/Folder.dart';
import 'package:front/models/core/Rule.dart';
import 'package:front/providers/CreateFolderProvider.dart';
import 'package:front/providers/UpdateFolderProvider.dart';
import 'package:front/views/homePage/dialog/UpdateFolderForm.dart';
import 'package:provider/provider.dart';

import 'CreateFolderForm.dart';

void updateFolderDialog({
  required BuildContext context,
  required Folder folder,
  required List<Rule> listRule,
}) {
  UpdateFolderProvider updateFolderProvider =
      Provider.of<UpdateFolderProvider>(context, listen: false);

  updateFolderProvider.listRule = [];
  updateFolderProvider.indexIcon = 0;

  showDialog(
      context: context,
      builder: (context) {
        return Dialog(
          shape:
              RoundedRectangleBorder(borderRadius: BorderRadius.circular(30.0)),
          child: updateFolderForm(
            context: context,
            folder: folder,
            listRule: listRule,
          ),
        );
      });
}
