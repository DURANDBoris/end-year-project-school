import 'package:flutter/material.dart';
import 'package:front/models/core/Organization.dart';
import 'package:front/models/helper/FolderHelper.dart';
import 'package:front/providers/DrawerProvider.dart';
import 'package:front/providers/AppBarProvider.dart';
import '/providers/HomePageProvider.dart';
import 'package:provider/provider.dart';

import '../../models/core/Folder.dart';
import '../homePage/dialog/CreateFolderDialog.dart';
import '../../utils/ListIcons.dart';

class DrawerWidget extends StatefulWidget {
  const DrawerWidget({Key? key}) : super(key: key);

  @override
  State<DrawerWidget> createState() => _DrawerWidgetState();
}

class _DrawerWidgetState extends State<DrawerWidget> {
  ScrollController scrollController = ScrollController();

  late HomePageProvider homePageProvider;
  late AppBarProvider appBarProvider;
  late DrawerProvider drawerProvider;

  late FolderHelper folderHelper;

  GlobalKey globalKey = GlobalKey();

  List<Widget> listCard(List<Folder> listFolder) {
    List<Widget> listWidget = [];
    for (Folder folder in listFolder) {
      listWidget.add(
        Card(
          child: ListTile(
            leading: Icon(listIcons[folder.iconIndex]),
            title: Text(folder.name),
            onTap: () {
              homePageProvider.folder = folder;
              homePageProvider.document = null;
            },
          ),
        ),
      );
    }
    return listWidget;
  }

  @override
  void initState() {
    super.initState();
    homePageProvider = Provider.of<HomePageProvider>(context, listen: false);
    appBarProvider = Provider.of<AppBarProvider>(context, listen: false);
    drawerProvider = Provider.of<DrawerProvider>(context, listen: false);

    folderHelper = FolderHelper(context: context);
  }

  @override
  Widget build(BuildContext context) {
    return Selector<DrawerProvider, Organization?>(
      selector: (_, provider) => provider.organization,
      builder: (_, organization, __) {
        return FutureBuilder(
          future: folderHelper.getFolders(drawerProvider: drawerProvider),
          builder: (context, AsyncSnapshot snapshot) {
            if (snapshot.connectionState != ConnectionState.done) {
              return Container();
            }
            return Selector<DrawerProvider, List<Folder>>(
              selector: (_, provider) => provider.listFolder,
              builder: (_, listFolder, __) {
                return Expanded(
                  flex: 15,
                  child: Stack(
                    children: [
                      Column(
                        children: [
                          Expanded(
                            child: Scrollbar(
                              controller: scrollController,
                              thumbVisibility: true,
                              trackVisibility: true,
                              child: SingleChildScrollView(
                                controller: scrollController,
                                child: Column(
                                  mainAxisSize: MainAxisSize.min,
                                  children: listCard(
                                    drawerProvider.listFolder,
                                  ),
                                ),
                              ),
                            ),
                          ),
                          Selector<AppBarProvider, bool>(
                              selector: (_, provider) => provider.isEditing,
                              builder: (_, organization, __) {
                                return appBarProvider.isEditing
                                    ? Container(
                                        decoration: BoxDecoration(
                                          border: Border.all(
                                              color: Colors.grey.shade500),
                                        ),
                                        child: ListTile(
                                          leading: Icon(listIcons[22]),
                                          title: Text("Non trier"),
                                          onTap: () {
                                            // homePageProvider.folder = folder;
                                          },
                                        ),
                                      )
                                    : Container();
                              }),
                          SizedBox(
                            height: 90,
                          )
                        ],
                      ),
                      Column(
                        key: globalKey,
                        children: [
                          Spacer(),
                          Container(
                            alignment: Alignment.center,
                            padding: EdgeInsets.only(bottom: 30, top: 10),
                            color: Colors.white,
                            child: ElevatedButton(
                              onPressed: () {
                                CreateFolderDialog(context);
                              },
                              child: Container(
                                padding: EdgeInsets.all(8),
                                child: Row(
                                  mainAxisSize: MainAxisSize.min,
                                  children: [
                                    Icon(
                                      Icons.create_new_folder,
                                    ),
                                    Text("New folder"),
                                  ],
                                ),
                              ),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                );
              },
            );
          },
        );
      },
    );
  }
}
