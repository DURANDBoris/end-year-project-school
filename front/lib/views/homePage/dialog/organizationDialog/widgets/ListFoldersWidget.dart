import 'package:flutter/material.dart';
import 'package:front/models/core/Folder.dart';
import 'package:front/models/core/Organization.dart';
import 'package:front/models/core/User.dart';
import 'package:front/models/helper/FolderHelper.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import 'package:front/providers/ListFoldersProvider.dart';
import 'package:front/providers/OrganizationDialogProvider.dart';
import 'package:provider/provider.dart';

Widget ListFoldersWidget({
  required Organization organization,
  required BuildContext context,
}) {
  ListFoldersProvider listFoldersProvider =
      Provider.of<ListFoldersProvider>(context, listen: false);
  listFoldersProvider.organization = organization;
  FolderHelper folderHelper = FolderHelper(context: context);
  return FutureBuilder<void>(
      future: folderHelper.getFolders(listFoldersProvider: listFoldersProvider),
      builder: (context, snapshot) {
        return Selector<ListFoldersProvider, List<Folder>>(
            selector: (context, provider) => provider.listFolder,
            builder: (context, data, child) {
              return Column(
                children: <Widget>[
                      Padding(
                        padding: const EdgeInsets.only(
                            top: 10, left: 10, bottom: 10),
                        child: Text(
                          "My Folders : ",
                          style: TextStyle(fontSize: 20),
                        ),
                      ),
                    ] +
                    data.map<Widget>((folder) {
                      return Card(
                        elevation: 3,
                        child: ListTile(
                          title: Text(folder.name),
                          onTap: () {},
                        ),
                      );
                    }).toList(),
              );
            });
      });
}
