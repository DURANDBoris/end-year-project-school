import 'dart:io';

import 'package:flutter/material.dart';
import 'package:front/Splashscreen.dart';
import 'package:front/providers/AccountProvider.dart';
import 'package:front/providers/AppBarProvider.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import 'package:front/providers/DrawerProvider.dart';
import 'package:front/providers/HomePageProvider.dart';
import 'package:front/providers/ListFoldersProvider.dart';
import 'package:front/providers/OrganizationDialogProvider.dart';
import 'package:front/providers/OrganizationProvider.dart';
import 'package:front/providers/SignInProvider.dart';
import 'package:provider/provider.dart';
import 'package:window_size/window_size.dart';

import 'providers/CreateFolderProvider.dart';
import 'providers/UpdateFolderProvider.dart';

void main() {
  WidgetsFlutterBinding.ensureInitialized();

  if (Platform.isWindows || Platform.isLinux || Platform.isMacOS) {
    setWindowTitle('File Flow');
    setWindowMinSize(const Size(1600, 900));
  }

  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      providers: [
        ChangeNotifierProvider(create: (_) => AppBarProvider()),
        ChangeNotifierProvider(create: (_) => HomePageProvider()),
        ChangeNotifierProvider(create: (_) => CreateFolderProvider()),
        ChangeNotifierProvider(create: (_) => UpdateFolderProvider()),
        ChangeNotifierProvider(create: (_) => AuthenticationProvider()),
        ChangeNotifierProvider(create: (_) => AccountProvider()),
        ChangeNotifierProvider(create: (_) => SignInProvider()),
        ChangeNotifierProvider(create: (_) => DrawerProvider()),
        ChangeNotifierProvider(create: (_) => OrganizationProvider()),
        ChangeNotifierProvider(create: (_) => OrganizationDialogProvider()),
        ChangeNotifierProvider(create: (_) => ListFoldersProvider()),
      ],
      child: MaterialApp(
        debugShowCheckedModeBanner: false,
        title: 'Flutter Demo',
        theme: ThemeData(
          primarySwatch: Colors.blue,
        ),
        home: Splashscreen(),
      ),
    );
  }
}
