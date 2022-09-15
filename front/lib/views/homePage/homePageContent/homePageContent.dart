import 'package:flutter/material.dart';
import 'package:front/models/helper/FolderHelper.dart';
import '/models/core/Folder.dart';
import '/views/homePage/homePageContent/FileWidget.dart';
import '/views/homePage/homePageContent/FolderView.dart';
import '/views/drawer/DrawerWidget.dart';
import 'package:provider/provider.dart';

import '../../../models/core/Document.dart';
import '../../../providers/AppBarProvider.dart';
import '../../../providers/HomePageProvider.dart';
import 'RuleView.dart';

class HomePageContent extends StatefulWidget {
  const HomePageContent({Key? key}) : super(key: key);

  @override
  State<HomePageContent> createState() => _HomePageContentState();
}

class _HomePageContentState extends State<HomePageContent> {
  late FolderHelper folderHelper;
  @override
  void initState() {
    folderHelper = FolderHelper(context: context);
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        DrawerWidget(),
        Expanded(
          flex: 85,
          child: Selector<HomePageProvider, Folder?>(
            selector: (_, provider) => provider.folder,
            builder: (_, folder, __) {
              if (folder == null) {
                return Row(
                  children: [
                    VerticalDivider(
                      thickness: 4,
                    ),
                    Spacer(
                      flex: 85,
                    ),
                  ],
                );
              }
              return FutureBuilder(
                future: folderHelper.getFolderContent(folder: folder),
                builder: ((context, snapshot) {
                  return Row(
                    children: [
                      Selector<AppBarProvider, bool>(
                        selector: (_, provider) => provider.isRuleOpen,
                        builder: (_, data, __) {
                          if (data) {
                            return VerticalDivider(
                              thickness: 4,
                            );
                          } else {
                            return Container();
                          }
                        },
                      ),
                      RuleWidget(),
                      VerticalDivider(
                        thickness: 4,
                      ),
                      FolderWidget(),
                      Selector<HomePageProvider, Document?>(
                          selector: (_, provider) => provider.document,
                          builder: (_, document, __) {
                            if (document != null) {
                              return VerticalDivider(
                                thickness: 4,
                              );
                            } else {
                              return VerticalDivider(
                                color: Colors.transparent,
                                thickness: 4,
                              );
                            }
                          }),
                      FileWidget(),
                    ],
                  );
                }),
              );
            },
          ),
        ),
      ],
    );
  }
}
