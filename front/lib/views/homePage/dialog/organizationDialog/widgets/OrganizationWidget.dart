import 'package:flutter/material.dart';
import 'package:front/models/core/Organization.dart';
import 'package:front/views/homePage/dialog/organizationDialog/widgets/InfosWidget.dart';
import 'package:front/views/homePage/dialog/organizationDialog/widgets/RolesWidget.dart';

Widget organizationWidget(
    {required Organization organization, required BuildContext context}) {
  return Column(
    mainAxisSize: MainAxisSize.min,
    children: [
      Container(
        alignment: Alignment.center,
        padding: EdgeInsets.symmetric(vertical: 15),
        child: Text(
          organization.name,
          style: TextStyle(fontSize: 25),
        ),
      ),
      Expanded(
        child: DefaultTabController(
          length: 2,
          child: Scaffold(
            backgroundColor: Colors.transparent,
            appBar: PreferredSize(
              preferredSize: Size.fromHeight(50),
              child: AppBar(
                backgroundColor: Colors.white,
                automaticallyImplyLeading: false,
                bottom: TabBar(
                  indicatorColor: Colors.blue,
                  labelColor: Colors.black,
                  tabs: [
                    Tab(child: Text("Organization infos")),
                    Tab(child: Text("Organization roles")),
                  ],
                ),
              ),
            ),
            body: TabBarView(
              children: [
                infosWidget(organization: organization, context: context),
                rolesWidget(organization: organization, context: context),
              ],
            ),
          ),
        ),
      ),
    ],
  );
}
