import 'dart:io';

import 'package:file_picker/file_picker.dart';
import 'package:flutter/material.dart';
import 'package:front/models/core/Organization.dart';
import 'package:front/models/service/UploadFileApi.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import 'package:front/providers/AppBarProvider.dart';
import 'package:front/providers/DrawerProvider.dart';
import 'package:front/providers/HomePageProvider.dart';
import '/providers/AppBarProvider.dart';
import '/views/appBar/SettingOverlay.dart';
import 'package:provider/provider.dart';

class MyAppBar extends StatefulWidget implements PreferredSizeWidget {
  const MyAppBar({Key? key}) : super(key: key);

  @override
  Size get preferredSize => Size.fromHeight(60);

  @override
  State<MyAppBar> createState() => _MyAppBar();
}

class _MyAppBar extends State<MyAppBar> {
  late AppBarProvider appBarProvider;
  late AuthenticationProvider authentication;
  late DrawerProvider drawerProvider;
  late HomePageProvider homePageProvider;
  @override
  void initState() {
    super.initState();
    appBarProvider = Provider.of<AppBarProvider>(context, listen: false);
    drawerProvider = Provider.of<DrawerProvider>(context, listen: false);
    homePageProvider = Provider.of<HomePageProvider>(context, listen: false);
    authentication =
        Provider.of<AuthenticationProvider>(context, listen: false);
  }

  @override
  Widget build(BuildContext context) {
    return AppBar(
      title: Row(
        children: [
          Selector<DrawerProvider, Organization?>(
              selector: ((p0, provider) => provider.organization),
              builder: (context, organization, child) {
                return authentication.user.listOrganization.isEmpty
                    ? Text("No organization")
                    : DropdownButton<Organization>(
                        value: organization,
                        focusColor: Colors.blue,
                        dropdownColor: Colors.blue,
                        items: authentication.user.listOrganization
                            .map<DropdownMenuItem<Organization>>(
                          (e) {
                            return DropdownMenuItem(
                              value: e,
                              child: Text(e.name),
                            );
                          },
                        ).toList(),
                        onChanged: ((value) {
                          if (value == null) return;
                          drawerProvider.organization = value;
                          homePageProvider.document = null;
                          homePageProvider.folder = null;
                        }),
                      );
              }),
          Center(
            child: Text("File Flow"),
          ),
          IconButton(
            onPressed: () {
              appBarProvider.isRuleOpen = !appBarProvider.isRuleOpen;
            },
            icon: Icon(
              Icons.add,
            ),
          ),
        ],
      ),
      actions: <Widget>[
        Selector<AppBarProvider, bool>(
          selector: (_, provider) => provider.isEditing,
          shouldRebuild: (previous, next) => true,
          builder: (_, isEditing, __) {
            return Padding(
              padding: const EdgeInsets.all(8.0),
              child: ElevatedButton(
                style: ButtonStyle(
                  backgroundColor:
                      MaterialStateProperty.all<Color>(Colors.white),
                ),
                onPressed: () {
                  appBarProvider.isEditing = !isEditing;
                },
                child: Row(
                  children: [
                    Text(
                      isEditing ? "Editing" : "Read only",
                      style: TextStyle(color: Colors.blue),
                    ),
                    Icon(
                      isEditing ? Icons.border_color : Icons.find_in_page,
                      color: Colors.blue,
                    ),
                  ],
                ),
              ),
            );
          },
        ),
        SizedBox(width: 10),
        IconButton(
          highlightColor: Colors.transparent,
          hoverColor: Colors.transparent,
          splashColor: Colors.transparent,
          onPressed: (() {
            showSettingOverlay(context);
          }),
          icon: Icon(Icons.settings),
        ),
        Container(width: 20),
      ],
    );
  }
}
