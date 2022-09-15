import 'package:flutter/cupertino.dart';
import 'package:front/models/core/Rule.dart';
import 'package:front/models/core/Folder.dart';
import 'package:front/models/helper/FolderHelper.dart';
import 'package:front/models/service/RuleApi.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import 'package:front/providers/HomePageProvider.dart';
import 'package:provider/provider.dart';

class RuleHelper {
  BuildContext context;
  late AuthenticationProvider authentication;
  late HomePageProvider homePageProvider;
  RuleApi ruleApi = RuleApi();

  RuleHelper({required this.context}) {
    authentication =
        Provider.of<AuthenticationProvider>(context, listen: false);
    homePageProvider = Provider.of<HomePageProvider>(context, listen: false);
  }

  Future<List<Rule>?> getFolderRules({required int idFolder}) async {
    return await ruleApi.getFolderRule(
      idFolder: idFolder,
      authentication: authentication,
    );
  }

  Future<Rule?> createFolderRule(
      {required Rule rule, required int idFolder}) async {
    return await ruleApi.createFolderRule(
      idFolder: idFolder,
      authentication: authentication,
      rule: rule,
    );
  }

  Future<Rule?> updateFolderRule(
      {required Rule rule, required int idFolder}) async {
    return await ruleApi.updateFolderRule(
      idFolder: idFolder,
      authentication: authentication,
      rule: rule,
    );
  }

  Future<bool> deleteRule({required int idRule, required int idFolder}) async {
    bool value = await ruleApi.deleteRule(
      idRule: idRule,
      authentication: authentication,
    );
    List<Rule>? listRule = await getFolderRules(idFolder: idFolder);
    if (listRule == null) return true;
    homePageProvider.listRule = listRule;
    return value;
  }
}
